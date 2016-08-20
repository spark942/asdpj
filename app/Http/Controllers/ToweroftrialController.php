<?php namespace App\Http\Controllers;

use DB;
use Lang;
use File;
use Input;
use Auth;

class ToweroftrialController extends Controller {

  private $_env = 'character';
  private $_karmaunit = ['700', '800', '900', '1000', '1100', '1200', '1300', '1400', '1500', '1600'];
  private $_votemultipler = [
    'firstpassed' => 1,
    'passed' => 0.5,
    'failed' => -0.6,
    'luck' => 0.5
    ];


  public function lang() {
    echo json_encode(Lang::get('character'), JSON_NUMERIC_CHECK);
  }

  public function currentTot(){
    $_action = 'tot';
    $_viewtype = 'tot/current';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );
    $curRound = $this->currentRound();
    $_viewdata['totround'] = $curRound;
    $_viewdata['round_ts_start'] = $this->roundTimestamp();
    $_viewdata['round_ts_end'] = $this->roundTimestamp(null, true);
    $_viewdata['round_ts_sincestart'] = $this->timeSinceRound();
    $_viewdata['round_ts_left'] = $this->timeSinceRound(null, true);

    $unitBookFileJson = GamejsonController::unitBook();
    $textBookFileJson = GamejsonController::textBook();

    // get current tower of trial solutions

    $_viewdata['solutions_json'] = json_encode($this->formattedSolutions());
    // get unit book with eng
    $unitBook = array();
    foreach ($unitBookFileJson['main']['unitList']['unit'] as $uKey => $uData) {
      if ($uData['cost'] != '0') {
        $names = [];
        foreach ($textBookFileJson['main']['textList']['text'] as $key => $tData) {
          if ($tData['id'] == 'UNIT_NAME_'.$uData['kindNum']) {
            $names = $textBookFileJson['main']['textList']['text'][$key];
          }
        }

        $unitBook[] = array(
          'unitId' => intval($uData['kindNum']),
          'tribe' => $uData['tribe'],
          'className' => $uData['className'],
          'name' => $uData['name'],
          'names' => $names,
          'evolId' => $uData['evolKindNum'],
          'attackType' => $uData['attackType'],
          'isAirUnit' => $uData['isAirUnit'],
          'damageType' => $uData['damageType'],
          'rank' => $uData['rank'],
          'sex' => $uData['sex'],
          'shop' => $uData['shop'],
          'showBook' => $uData['showBook'],
          'groundAir' => $uData['groundAir'],
          );
      }
    }


    //dd($textBookFileJson['main']['textList']['text']);

    //dd($ENDTIME - $STARTIME);
    //dd($unitBook);
    $_viewdata['roundvotes'] = json_encode($this->getRoundVotes($curRound));
    if (Auth::check()) {
      $_viewdata['myroundvotes'] = json_encode($this->getMyVotes($curRound));
    }
    $_viewdata['unitbook_json'] = json_encode($unitBook);
    return view($_viewtype, $_viewdata);
  }

  /****************************
   * Ajax - Logged only : add a new solution
   ****************************/
  public function newsolution() {
    // collect round and floor from POST data
    $round = Input::get('round');
    $floor = Input::get('floor');

    $ajaxResponse = ['msg' => ''];
    // dont save if 10 minutes left
    if($this->timeSinceRound(null, true) > -600) {
      $ajaxResponse['msg'] = 'newRoundSoon';
    } elseif ($round != $this->currentRound()) {
      $ajaxResponse['msg'] = 'cannotSaveSolutionForOtherRound';
    } elseif ($floor > $this->maxFloor()) {
      $ajaxResponse['msg'] = 'floorNotAvailable';
    } else {
      // collect the rest of POST data
      $solution = Input::get('solution');
      $solutionparams = Input::get('solutionparams');
      // calculate team karma
      $karma = $this->teamkarma($floor, $solution);

      //prepare data save to db
      $data = array(
        'tot_round' => $round,
        'floor'     => $floor,
        'user_id'   => Auth::user()->id,
        'karma'     => $solutionparams['luck'] ? 50 : 100,
        'maxrank'   => $karma['maxrank'],
        'maxevol'   => $karma['maxevol'],
        'gamespeed' => $solutionparams['gamespeed'],
        'luck'      => $solutionparams['luck'],
        'karma_multiplier'     => $karma['karmaMultiplier'],
        'note'      => $solutionparams['note'],
        'unit_id_1'    => $solution[0]['unitId'],
        'unit_rank_1'  => $solution[0]['rank'],
        'unit_trans_1' => $solution[0]['trans']
      );
      for ($i=1; $i < 12; $i++) {
        if (!isset($solution[$i])) {
          break;
        }
        $data['unit_id_'.($i+1)]    = $solution[$i]['unitId'];
        $data['unit_rank_'.($i+1)]  = $solution[$i]['rank'];
        $data['unit_trans_'.($i+1)] = $solution[$i]['trans'];
      }
      $sid = DB::table('tot_solutions')->insertGetId($data);

      // vote for my solution
      $solution = $this->getSolutions(null, false, $sid)[0];
      $voteType = $solutionparams['luck'] == 1 ? 1 : 2;
      $calculatedKarma = $this->karmaPoint($voteType, $solution->karmamultiplier, $solution->luck);
      //prepare data save to db
      $data = array(
        'round' => $solution->round,
        'floor' => $solution->floor,
        'tot_solution_id' => $solution->sid,
        'user_id'    => Auth::user()->id,
        'vote_type'  => $voteType,
        'karma_vote' => $calculatedKarma,
        'old_karma'  => $solution->karma
      );
      DB::table('tot_solution_votes')->insert($data);

      //update solution karma
      DB::table('tot_solutions')->where('id', $sid)->update(['karma' => $solution->  karma + $calculatedKarma]);
      $ajaxResponse['msg'] = 'ok';
    }

    $ajaxResponse['solutions'] = $this->formattedSolutions();
    $ajaxResponse['myroundvotes'] = $this->getMyVotes($round);
    $ajaxResponse['roundvotes'] = $this->getRoundVotes($this->currentRound());
    echo json_encode($ajaxResponse, JSON_NUMERIC_CHECK);
  }

  public function votesolution() {
    $solutionID = Input::get('solutionID');
    $voteType   = Input::get('voteType');

    $ajaxResponse = ['msg' => ''];
    //check if this solution is from current round
    $solution = $this->getSolutions(null, false, $solutionID)[0];

    if ($solution->round == $this->currentRound()) {
      $calculatedKarma = $this->karmaPoint($voteType, $solution->karmamultiplier, $solution->luck);
      //prepare data save to db
      $data = array(
        'round' => $solution->round,
        'floor' => $solution->floor,
        'tot_solution_id' => $solution->sid,
        'user_id'    => Auth::user()->id,
        'vote_type'  => $voteType,
        'karma_vote' => $calculatedKarma,
        'old_karma'  => $solution->karma
      );
      DB::table('tot_solution_votes')->insert($data);

      //update solution karma
      DB::table('tot_solutions')->where('id', $solutionID)->update(['karma' => $solution->  karma + $calculatedKarma]);
      $ajaxResponse['myroundvotes'] = $this->getMyVotes($this->currentRound());
      $ajaxResponse['roundvotes'] = $this->getRoundVotes($solution->round);
      $ajaxResponse['msg'] = 'ok';
    } else {
      $ajaxResponse['msg'] = 'cannotVoteForSolutionOfPreviousRound';
    }
    echo json_encode($ajaxResponse, JSON_NUMERIC_CHECK);
  }


  /****************************
   * Karma calculators
   ****************************/
  private function unitkarma($unit) {
    $karmaindex = $unit['rank'] + $unit['trans'];
    return $this->_karmaunit[$karmaindex];
  }

  private function teamkarma($floor, $solution) {
    $totalkarma = 0;
    $solutionkarma = [];
    $maxrank = 0;
    $maxevol = 0;

    foreach ($solution as $uk => $ud) {
      $tmp = $this->unitkarma($ud);
      $solutionkarma[] = $tmp;
      $totalkarma += $tmp;
      if ($ud['rank'] > $maxrank) {
        $maxrank = $ud['rank'];
      }
      if (($ud['trans'] + 2) > $maxevol) {
        $maxevol = $ud['trans'] + 2;
      }
    }

    if (count($solution) > 1) {
      $teamkarma = $totalkarma / (1 + count($solution)*0.1);
    } else {
      $teamkarma = $totalkarma;
    }

    $karmamultiplier = ((10000 - $teamkarma)* ($floor/2))/100;

    return array(
      'karmaMultiplier' => $karmamultiplier,
      'maxrank' => $maxrank,
      'maxevol' => $maxevol,
      'team' => $teamkarma,
      'total' => $totalkarma,
      'units' => $solutionkarma);
  }

  private function karmaPoint($voteType, $karmaMultiplier, $luck = 0) {
    $luckMultipler = 1;
    if ($luck == 1) {
      $luckMultipler = $this->_votemultipler['luck'];
    }
    if ($voteType == 2) {
      return $karmaMultiplier * $this->_votemultipler['firstpassed'] * $luckMultipler;
    } else if ($voteType == 1) {
      return $karmaMultiplier * $this->_votemultipler['passed'] * $luckMultipler;
    } else if ($voteType == 0) {
      return $karmaMultiplier * $this->_votemultipler['failed'] * $luckMultipler;
    } else {
      return false;
    }
  }

  /****************************
   * TOT ROUND
   ****************************/
  // 1459209600 = 3/29/2016
  private $_startOfTotTimestamp = 1459209600;
  // Time in second per round = 259200 (3 * 24 * 60 * 60)
  private $_secondsPerRound = 259200;

  public function currentRound() {
    $currentRound = intval((time() - $this->_startOfTotTimestamp) / $this->_secondsPerRound);
    return $currentRound;
  }

  public function roundTimestamp($round = null, $end = false) {
    if ($round == null) {
      $round = $this->currentRound();
    }
    if ($end) {
      $round++;
    }
    return $this->_startOfTotTimestamp + ($round * $this->_secondsPerRound);
  }

  public function timeSinceRound($round = null, $left = false) {
    if ($round == null) {
      $round = $this->currentRound();
    }
    if ($left) {
      $round++;
    }
    return time() - $this->roundTimestamp($round);
  }

  public function maxFloor() {
    return 32;
  }

  /* return array of solutions by floor */
  public function formattedSolutions($round = null) {
    if ($round == null) {
      $round = $this->currentRound();
    }
    $solutionData = $this->getSolutions($round);
    $roundSolutions = [];
    for ($i=0; $i < $this->maxFloor(); $i++) { 
      $tmpFloor = [
        'floorn'      => $i + 1,
        'isCollapsed' => false,
        'solutions'   => []
      ];
      foreach ($solutionData as $sk => $sv) {
        if ($sv->floor == ($i+1)){
          $tmpSolution = [
            'sid'      => $sv->sid,
            'karma'      => $sv->karma,
            'karmaMulti' => $sv->karmamultiplier,
            'maxrank'    => $sv->maxrank,
            'maxevol'    => $sv->maxevol,
            'gamespeed'  => $sv->gamespeed,
            'note'       => $sv->note,
            'luck'       => $sv->luck,
            'units'      => []
          ];
          for ($u=0; $u < 12; $u++) { 
            if(!$sv->{'unit_id_'.($u+1)}){
              break;
            }
            $tmpSolution['units'][] = [
              'unitId' => $sv->{'unit_id_'.($u+1)},
              'rank'   => $sv->{'unit_rank_'.($u+1)},
              'trans'  => $sv->{'unit_trans_'.($u+1)}
            ];
          }

          $tmpFloor['solutions'][] = $tmpSolution;
        }
      }

      $roundSolutions[$i] = $tmpFloor;
    }
    return $roundSolutions;
  }

  public function selfProgression($round = null) {
    if ($round == null) {
      $round = $this->currentRound();
    }

  }

  public function getMyVotes($round = 0) {
    if ($round == 0) {
      //all votes
      return DB::table('tot_solution_votes')
        ->where('tot_solution_votes.user_id', '=', Auth::user()->id)
        ->select(
          'tot_solution_votes.id as id',
          'tot_solution_votes.round as round',
          'tot_solution_votes.floor as floor',
          'tot_solution_votes.tot_solution_id as sid',
          'tot_solution_votes.vote_type as votetype',
          'tot_solution_votes.karma_vote as karmavote')
        ->get();
    } else {
      return DB::table('tot_solution_votes')
        ->where('tot_solution_votes.user_id', '=', Auth::user()->id)
        ->where('tot_solution_votes.round', '=', $round)
        ->select(
          'tot_solution_votes.id as id',
          'tot_solution_votes.round as round',
          'tot_solution_votes.floor as floor',
          'tot_solution_votes.tot_solution_id as sid',
          'tot_solution_votes.vote_type as votetype',
          'tot_solution_votes.karma_vote as karmavote')
        ->get();
    }
  }

  public function getRoundVotes($round) {
    return DB::table('tot_solution_votes')
        ->where('tot_solution_votes.round', '=', $round)
        ->select(
          'tot_solution_votes.id as id',
          'tot_solution_votes.round as round',
          'tot_solution_votes.floor as floor',
          'tot_solution_votes.tot_solution_id as sid',
          'tot_solution_votes.vote_type as votetype',
          'tot_solution_votes.karma_vote as karmavote')
        ->get();
  }

  /**
   * DB functions
   */

  /* return table object */
  public function getSolutions($totRound = null, $multiple = true, $solutionID = null) {
    if ($multiple) {
      return DB::table('tot_solutions')
        ->where('tot_solutions.tot_round', '=', $totRound)
        ->select(
          'tot_solutions.id as sid',
          'tot_solutions.tot_round as round',
          'tot_solutions.floor as floor',
          'tot_solutions.karma as karma',
          'tot_solutions.maxrank as maxrank',
          'tot_solutions.maxevol as maxevol',
          'tot_solutions.gamespeed as gamespeed',
          'tot_solutions.karma_multiplier as karmamultiplier',
          'tot_solutions.note as note',
          'tot_solutions.luck as luck',
          'tot_solutions.unit_id_1 as unit_id_1',
          'tot_solutions.unit_rank_1 as unit_rank_1',
          'tot_solutions.unit_trans_1 as unit_trans_1',
          'tot_solutions.unit_id_2 as unit_id_2',
          'tot_solutions.unit_rank_2 as unit_rank_2',
          'tot_solutions.unit_trans_2 as unit_trans_2',
          'tot_solutions.unit_id_3 as unit_id_3',
          'tot_solutions.unit_rank_3 as unit_rank_3',
          'tot_solutions.unit_trans_3 as unit_trans_3',
          'tot_solutions.unit_id_4 as unit_id_4',
          'tot_solutions.unit_rank_4 as unit_rank_4',
          'tot_solutions.unit_trans_4 as unit_trans_4',
          'tot_solutions.unit_id_5 as unit_id_5',
          'tot_solutions.unit_rank_5 as unit_rank_5',
          'tot_solutions.unit_trans_5 as unit_trans_5',
          'tot_solutions.unit_id_6 as unit_id_6',
          'tot_solutions.unit_rank_6 as unit_rank_6',
          'tot_solutions.unit_trans_6 as unit_trans_6',
          'tot_solutions.unit_id_7 as unit_id_7',
          'tot_solutions.unit_rank_7 as unit_rank_7',
          'tot_solutions.unit_trans_7 as unit_trans_7',
          'tot_solutions.unit_id_8 as unit_id_8',
          'tot_solutions.unit_rank_8 as unit_rank_8',
          'tot_solutions.unit_trans_8 as unit_trans_8',
          'tot_solutions.unit_id_9 as unit_id_9',
          'tot_solutions.unit_rank_9 as unit_rank_9',
          'tot_solutions.unit_trans_9 as unit_trans_9',
          'tot_solutions.unit_id_10 as unit_id_10',
          'tot_solutions.unit_rank_10 as unit_rank_10',
          'tot_solutions.unit_trans_10 as unit_trans_10',
          'tot_solutions.unit_id_11 as unit_id_11',
          'tot_solutions.unit_rank_11 as unit_rank_11',
          'tot_solutions.unit_trans_11 as unit_trans_11',
          'tot_solutions.unit_id_12 as unit_id_12',
          'tot_solutions.unit_rank_12 as unit_rank_12',
          'tot_solutions.unit_trans_12 as unit_trans_12')
        ->get();
    } else {
      return DB::table('tot_solutions')
        ->where('tot_solutions.id', '=', $solutionID)
        ->select(
          'tot_solutions.id as sid',
          'tot_solutions.tot_round as round',
          'tot_solutions.floor as floor',
          'tot_solutions.karma as karma',
          'tot_solutions.maxrank as maxrank',
          'tot_solutions.maxevol as maxevol',
          'tot_solutions.gamespeed as gamespeed',
          'tot_solutions.karma_multiplier as karmamultiplier',
          'tot_solutions.note as note',
          'tot_solutions.luck as luck',
          'tot_solutions.unit_id_1 as unit_id_1',
          'tot_solutions.unit_rank_1 as unit_rank_1',
          'tot_solutions.unit_trans_1 as unit_trans_1',
          'tot_solutions.unit_id_2 as unit_id_2',
          'tot_solutions.unit_rank_2 as unit_rank_2',
          'tot_solutions.unit_trans_2 as unit_trans_2',
          'tot_solutions.unit_id_3 as unit_id_3',
          'tot_solutions.unit_rank_3 as unit_rank_3',
          'tot_solutions.unit_trans_3 as unit_trans_3',
          'tot_solutions.unit_id_4 as unit_id_4',
          'tot_solutions.unit_rank_4 as unit_rank_4',
          'tot_solutions.unit_trans_4 as unit_trans_4',
          'tot_solutions.unit_id_5 as unit_id_5',
          'tot_solutions.unit_rank_5 as unit_rank_5',
          'tot_solutions.unit_trans_5 as unit_trans_5',
          'tot_solutions.unit_id_6 as unit_id_6',
          'tot_solutions.unit_rank_6 as unit_rank_6',
          'tot_solutions.unit_trans_6 as unit_trans_6',
          'tot_solutions.unit_id_7 as unit_id_7',
          'tot_solutions.unit_rank_7 as unit_rank_7',
          'tot_solutions.unit_trans_7 as unit_trans_7',
          'tot_solutions.unit_id_8 as unit_id_8',
          'tot_solutions.unit_rank_8 as unit_rank_8',
          'tot_solutions.unit_trans_8 as unit_trans_8',
          'tot_solutions.unit_id_9 as unit_id_9',
          'tot_solutions.unit_rank_9 as unit_rank_9',
          'tot_solutions.unit_trans_9 as unit_trans_9',
          'tot_solutions.unit_id_10 as unit_id_10',
          'tot_solutions.unit_rank_10 as unit_rank_10',
          'tot_solutions.unit_trans_10 as unit_trans_10',
          'tot_solutions.unit_id_11 as unit_id_11',
          'tot_solutions.unit_rank_11 as unit_rank_11',
          'tot_solutions.unit_trans_11 as unit_trans_11',
          'tot_solutions.unit_id_12 as unit_id_12',
          'tot_solutions.unit_rank_12 as unit_rank_12',
          'tot_solutions.unit_trans_12 as unit_trans_12')
        ->get();
    }
  }



  /* zaodjoizeqjfoizjqefoizjef delete */

}