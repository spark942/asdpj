<?php namespace App\Http\Controllers;

use DB;
use Lang;

class SkillController extends Controller {

  private $_env = 'skill';


  public function exploreSS() {
    $_action = 'explore';
    $_viewtype = 'skill/exploreSS';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );


    $_viewdata['char_localization'] = json_encode(Lang::get('character'));
    $_viewdata['weapon_categories_localization'] = json_encode(Lang::get('saocr_weapon_categories'));

    return view($_viewtype, $_viewdata);
  }

  public function exploreLS() {
    $_action = 'explore';
    $_viewtype = 'skill/exploreLS';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    $leaderSkills = $this->getLeaderSkills();

    foreach ($leaderSkills as $leaderSkill) {
      $chars = $this->getCharKindsByLeaderSkill($leaderSkill->id);
      foreach ($chars as $char) {
        if (substr(strval($char->id), -1) == '6') {
          $char->imgid = $char->id - 16;
        } else {
          $char->imgid = $char->id;
        }
      }

      $leaderSkill->chars = $chars;
    }

    $_viewdata['leader_skills_json'] = json_encode($leaderSkills);

    /*
     * Load data js files
     */
    $charkind_data_file = DB::table('f_db_versions')
      ->leftJoin('f_character_kind_versions', 'f_db_versions.file_id', '=', 'f_character_kind_versions.id')
      ->where('tablename', 'table_character_kind')
      ->select('f_character_kind_versions.date as date')
      ->first();
    $charkindDataFileName = 'table_character_kind_' . $charkind_data_file->date . '.js';
    $_viewdata['ck_data_js_file'] = $charkindDataFileName;

    return view($_viewtype, $_viewdata);
  }

  public function getSS($charID, $name = null) {
    $_action = 'show';
    $_viewtype = 'character/show';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action,
      'char_kind_id'    => $charID
      );

    //var_dump($beforeChar);
    //var_dump($_viewdata);
    return view($_viewtype, $_viewdata);
  }

  public function getLS($charID, $name = null) {
    $_action = 'show';
    $_viewtype = 'character/show';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action,
      'char_kind_id'    => $charID
      );

    //var_dump($beforeChar);
    //var_dump($_viewdata);
    return view($_viewtype, $_viewdata);
  }

  /**
   * DB functions
   */


  public function getLeaderSkills() {
    return DB::table('saocr_leader_skills')
      ->orderBy('id', 'asc')
      ->select('id', 'name', 'description')
      ->get();
  }

  public function getCharKindsByLeaderSkill($leaderSkillID) {
    return DB::table('saocr_character_awake_leader_skills')
      ->leftJoin(
        'saocr_character_kinds',
          'saocr_character_awake_leader_skills.m_character_kind_id', '=', 'saocr_character_kinds.id')
      ->leftJoin(
        'saocr_character_rarities',
          'saocr_character_kinds.m_rarity_id', '=', 'saocr_character_rarities.id')
      ->orderBy('id', 'asc')
      ->where('saocr_character_awake_leader_skills.m_leader_skill_id', '=', $leaderSkillID)
      ->select(
        'saocr_character_kinds.id',
        'saocr_character_kinds.name',
        'saocr_character_kinds.name_english',
        'saocr_character_kinds.head_name',
        'saocr_character_rarities.rarity_num as rarity_num')
      ->get();
  }

}