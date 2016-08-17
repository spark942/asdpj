<?php namespace App\Http\Controllers;

use DB;
use Lang;

class QuestController extends Controller {

  private $_env = 'quest';

  public function lang() {
    //echo json_encode(Lang::get('character'), JSON_NUMERIC_CHECK);
  }

  public function explore($type = null) {
    $_action = 'explore';
  	if ($type == 'normal') {
  		$_viewtype = 'quest/exploreNormal';
  	} else if ($type == 'extra') {
  		$_viewtype = 'quest/exploreExtra';
  	} else if ($type == 'event') {
  		$_viewtype = 'quest/exploreEvent';
  	} else {
  		$_viewtype = 'quest/explore';
  	}
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    $questWorlds 	 = self::getQuestWorlds();
    $questChapters = self::getQuestChapters();
    $questGroups 	 = self::getQuestGroups();
    $quests        = self::getQuests();

    $_viewdata['quest_worlds_json']   = json_encode($questWorlds);
    $_viewdata['quest_chapters_json'] = json_encode($questChapters);
    $_viewdata['quest_groups_json']   = json_encode($questGroups);
    $_viewdata['quests_json']         = json_encode($quests);


    /*
     * Load data js files
     */
    $quest_worlds_data_file = DB::table('f_db_versions')
      ->leftJoin('f_quest_worlds_versions', 'f_db_versions.file_id', '=', 'f_quest_worlds_versions.id')
      ->where('tablename', 'table_quest_worlds')
      ->select('f_quest_worlds_versions.date as date')
      ->first();
    $quest_chapters_data_file = DB::table('f_db_versions')
      ->leftJoin('f_quest_chapters_versions', 'f_db_versions.file_id', '=', 'f_quest_chapters_versions.id')
      ->where('tablename', 'table_quest_chapters')
      ->select('f_quest_chapters_versions.date as date')
      ->first();
    $quest_groups_data_file = DB::table('f_db_versions')
      ->leftJoin('f_quest_groups_versions', 'f_db_versions.file_id', '=', 'f_quest_groups_versions.id')
      ->where('tablename', 'table_quest_groups')
      ->select('f_quest_groups_versions.date as date')
      ->first();
    $quests_data_file = DB::table('f_db_versions')
      ->leftJoin('f_quests_versions', 'f_db_versions.file_id', '=', 'f_quests_versions.id')
      ->where('tablename', 'table_quests')
      ->select('f_quests_versions.date as date')
      ->first();

    $questworldsDataFileName 	 = 'table_quest_worlds_' . $quest_worlds_data_file->date . '.js';
    $questchaptersDataFileName = 'table_quest_chapters_' . $quest_chapters_data_file->date . '.js';
    $questgroupsDataFileName   = 'table_quest_groups_' . $quest_groups_data_file->date . '.js';
    $questsDataFileName        = 'table_quests_' . $quests_data_file->date . '.js';

    $_viewdata['qw_data_js_file'] = $questworldsDataFileName;
    $_viewdata['qc_data_js_file'] = $questchaptersDataFileName;
    $_viewdata['qg_data_js_file'] = $questgroupsDataFileName;
    $_viewdata['q_data_js_file']  = $questsDataFileName;

    return view($_viewtype, $_viewdata);
  }

  public function getCK($questID, $name = null) {
    $_action = 'show';
    $_viewtype = 'quest/show';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action,
      'quest_id'    => $questID
      );

    //var_dump($beforeChar);
    //var_dump($_viewdata);
    return view($_viewtype, $_viewdata);
  }



  /**
   * DB functions
   */

  static public function getQuestWorlds($full = false) {
  	$columns = [
  		'saocr_quest_worlds.id as id',
      'saocr_quest_worlds.name as name',
      'saocr_quest_worlds.acronym_name as acronymName'
  	];

  	if ($full) {
			$columns[] = 'saocr_quest_worlds.description as description';
			$columns[] = 'saocr_quest_worlds.story_name as storyName';
			$columns[] = 'saocr_quest_worlds.story_description as storyDescription';
  	}
  	return DB::table('saocr_quest_worlds')
      ->where('saocr_quest_worlds.t_version_id', '=', env('DB_VERSION_ID'))
  		->select($columns)->get();
  }

  static public function getQuestChapters($full = false) {
  	$columns = [
  		'saocr_quest_chapters.id as id',
  		'saocr_quest_chapters.chapter_order as chapterOrder',
  		'saocr_quest_chapters.m_parent_world_id as parentWorldID',
  		'saocr_quest_chapters.m_quest_chapter_group_id as questChapterGroupID',
  		'saocr_quest_chapters.previous_chapter_id as acronymName'
  	];

  	if ($full) {
			$columns[] = 'saocr_quest_chapters.name as name';
			$columns[] = 'saocr_quest_chapters.description as description';
  	}
  	return DB::table('saocr_quest_chapters')
      ->where('saocr_quest_chapters.t_version_id', '=', env('DB_VERSION_ID'))
      ->select($columns)->get();
  }

  static public function getQuestGroups($full = false) {
  	$columns = [
      'saocr_quest_groups.id as id',
      'saocr_quest_groups.quest_group_order as groupOrder',
      'saocr_quest_groups.m_quest_chapter_id as questChapterID',
      'saocr_quest_groups.previous_quest_group_id as previousQuestGroupID'
  	];

  	if ($full) {
			$columns[] = 'saocr_quest_groups.name as name';
			$columns[] = 'saocr_quest_groups.description as description';
  	}
  	return DB::table('saocr_quest_groups')
      ->where('saocr_quest_groups.t_version_id', '=', env('DB_VERSION_ID'))
      ->select($columns)->get();
  }

  static public function getQuests($full = false) {
  	$columns = [
      'saocr_quests.id as id',
      'saocr_quests.quest_order as questOrder',
      'saocr_quests.m_quest_group_id as questGroupID',
      'saocr_quests.required_stamina as requiredStamina',
      'saocr_quests.gained_player_exp as gainedPlayerExp',
      'saocr_quests.gained_character_exp as gainedCharacterExp',
      'saocr_quests.gained_money as gainedMoney',
      'saocr_quests.m_elemental_id as elementalID',
      'saocr_quests.quest_category as questCategory',
      'saocr_quests.battle_num as battleNum'
  	];

  	if ($full) {
			$columns[] = 'saocr_quests.name as name';
			$columns[] = 'saocr_quests.description as description';
  	}

  	return DB::table('saocr_quests')
      ->where('saocr_quests.t_version_id', '=', env('DB_VERSION_ID'))
      ->select($columns)->get();
  }


}