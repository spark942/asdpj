<?php namespace App\Http\Controllers;

use Auth;
use Request;
use Redirect;
use App;
use Storage;
use DB;

class AdminController extends Controller {
  private $_env = 'admin';



  public function dashboard(){
    $_action   = 'admindashboard';
    $_viewtype = 'admin/dashboard';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    if (! Auth::user()) {
      return Redirect::to('/');
    }

    $rights = explode('-', Auth::user()->rights);

    /*
      $rights[0]
        => 1 : can access admin
        => 2 : full access
      $rights[1]
        => 01 : can see translation
        => 02 : can manage translation
      $rights[2]
        => 01 : can see articles
        => 02 : can write articles
      $rights[3]
        => 01 : can see events
        => 02 : can manage events
    */

    if ($rights[0] >= 1) {
      // set rights for angular
      // 10 for ful rights
      $categoryRights = [
        "overview"      => $rights[0] == 1 ? (int) $rights[0] : 99,
        "dbtranslation" => $rights[0] == 1 ? (int) $rights[1] : 99,
        "events"        => $rights[0] == 1 ? (int) $rights[2] : 99,
        "articles"      => $rights[0] == 1 ? (int) $rights[3] : 99,
      ];

      $_viewdata['admin_category_rights_json'] = json_encode($categoryRights, JSON_NUMERIC_CHECK);
    } else {
      return Redirect::to('/');
    }

    return view($_viewtype, $_viewdata);
  }

  public function admins(){

  }

  public function myprofile(){
    $_action   = 'myprofile';
    $_viewtype = 'admin/self';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    if (!Auth::check()) {
      return Redirect::to('/');
    } else {
      
    }

    return view($_viewtype, $_viewdata);
  }

  public function ajaxDbtable() {
    $dbtableName = Request::input('dbtable');
    $data = null;
    /**
     * SPECIFIC TABLES
     */
    if ($dbtableName == 'table_character_kind') {
      $data = App::call('\App\Http\Controllers\CharacterController::getKinds');
    } else if ($dbtableName == 'table_quest_worlds') {
      $data = App::call('\App\Http\Controllers\QuestController::getQuestWorlds', ['full' => true]);
    } else if ($dbtableName == 'table_quest_chapters') {
      $data = App::call('\App\Http\Controllers\QuestController::getQuestChapters', ['full' => true]);
    } else if ($dbtableName == 'table_quest_groups') {
      $data = App::call('\App\Http\Controllers\QuestController::getQuestGroups', ['full' => true]);
    } else if ($dbtableName == 'table_quests') {
      $data = App::call('\App\Http\Controllers\QuestController::getQuests', ['full' => true]);
    }

    echo json_encode([$dbtableName, $data], JSON_NUMERIC_CHECK);
  }

  public function getDataFileList() {
    $dbtableName = Request::input('dbtable');

    /*
      $files = Storage::disk('localdbdata')->files('/');

      $tableFiles = [];
      foreach($files as $file){
          if(($a = strstr($file, $dbtableName)) !== false){
              $tableFiles[] = $file;
          }
      }
      sort($tableFiles);
    */
    /**
     * SPECIFIC TABLES
     */
    if ($dbtableName == 'table_character_kind') {
      $tableName = 'f_character_kind_versions';
    } else if ($dbtableName == 'table_quest_worlds') {
      $tableName = 'f_quest_worlds_versions';
    } else if ($dbtableName == 'table_quest_chapters') {
      $tableName = 'f_quest_chapters_versions';
    } else if ($dbtableName == 'table_quest_groups') {
      $tableName = 'f_quest_groups_versions';
    } else if ($dbtableName == 'table_quests') {
      $tableName = 'f_quests_versions';
    }

    $tableFiles = DB::table($tableName)
      ->leftJoin('users', $tableName.'.user_id', '=', 'users.id')
      ->select(
        $tableName.'.id as id',
        $tableName.'.date as date',
        $tableName.'.notes as notes',
        'users.name as adminname')
      ->get();
    echo json_encode(array_reverse($tableFiles));
  }

  public function ajaxDataSaveFile() {
    $dbtableName = Request::input('dbtable');
    $fileNote    = Request::input('note');
    $data        = Request::input('data');

    $dataFormattedForFile = [];

    /**
     * SPECIFIC TABLES
     */
    if ($dbtableName == 'table_character_kind') {
      foreach ($data as $key => $char) {
        $newchar = [
          'rawHeadName'       => $char['originalHeadName'],
          'rawName'           => $char['originalName'],
          'displayedHeadName' => $char['displayedHeadName'],
          'displayedName'     => $char['displayedName'],
          'displayedWorld'    => $char['displayedWorld'],
        ];
        $dataFormattedForFile[$char['originalID']] = $newchar;
      }
      $tableName = 'f_character_kind_versions';
    } else if ($dbtableName == 'table_quest_worlds') {
      foreach ($data as $key => $qworld) {
        $newqworld = [
          'rawAcronym'               => $qworld['originalAcronymName'],
          'rawStoryName'             => $qworld['originalStoryName'],
          'rawStoryDescription'      => $qworld['originalStoryDescription'],
          'displayedStoryName'       => $qworld['displayedStoryName'],
          'displayedStoryDescription' => $qworld['displayedStoryDescription'],
        ];
        $dataFormattedForFile[$qworld['originalID']] = $newqworld;
      }
      $tableName = 'f_quest_worlds_versions';
    } else if ($dbtableName == 'table_quest_chapters') {
      foreach ($data as $key => $qchap) {
        $newqchap = [
          'rawName'             => $qchap['originalName'],
          'rawDescription'      => $qchap['originalDescription'],
          'displayedName'       => $qchap['displayedName'],
          'displayedDescription' => $qchap['displayedDescription'],
        ];
        $dataFormattedForFile[$qchap['originalID']] = $newqchap;
      }
      $tableName = 'f_quest_chapters_versions';
    } else if ($dbtableName == 'table_quest_groups') {
      foreach ($data as $key => $qgroup) {
        $newqgroup = [
          'rawName'             => $qgroup['originalName'],
          'rawDescription'      => $qgroup['originalDescription'],
          'displayedName'       => $qgroup['displayedName'],
          'displayedDescription' => $qgroup['displayedDescription'],
        ];
        $dataFormattedForFile[$qgroup['originalID']] = $newqgroup;
      }
      $tableName = 'f_quest_groups_versions';
    } else if ($dbtableName == 'table_quests') {
      foreach ($data as $key => $quest) {
        $newquest = [
          'rawName'             => $quest['originalName'],
          'rawDescription'      => $quest['originalDescription'],
          'displayedName'       => $quest['displayedName'],
          'displayedDescription' => $quest['displayedDescription'],
        ];
        $dataFormattedForFile[$quest['originalID']] = $newquest;
      }
      $tableName = 'f_quests_versions';
    }

    $curdate = date("Y-m-d_G-i-s");
    $fileinfo = 
'/* ********************************
 * '.$dbtableName.'
 * Author : '.Auth::user()->name.'
 * Note : '.$fileNote.'
 ******************************** */
var '.$dbtableName.' = ';

    $fileName = $dbtableName . '_' . $curdate . '.js';

    Storage::disk('localdbdata')
      ->put($fileName,$fileinfo . json_encode($dataFormattedForFile, JSON_PRETTY_PRINT));

    DB::table($tableName)->insert(array(
      'date' => $curdate,
      'notes'    => $fileNote,
      'user_id'  => Auth::user()->id
      ));

    echo json_encode(['kull', $fileNote, $dataFormattedForFile]);
  }

  public function ajaxUpdateFileUsedByTable() {
    $dbtableName = Request::input('dbtable');
    $fileID      = Request::input('fileID');

    $tableExist = DB::table('f_db_versions')->where('tablename', $dbtableName)->first();

    if ($tableExist) {
      DB::table('f_db_versions')
        ->where('tablename', $dbtableName)
        ->update(array('file_id' => $fileID));
    } else {
      DB::table('f_db_versions')
        ->insert(array('tablename' => $dbtableName, 'file_id' => $fileID));
    }
  }
  public function ajaxGetFileUsedByTable() {
    $dbtableName = Request::input('dbtable');
    $res = DB::table('f_db_versions')->where('tablename', $dbtableName)->pluck('file_id');
    echo json_encode(array("fileID" => $res));
  }

  public function addTableData() {
    $tables = [
      "saocr_accessories" => [
        "gametablename" => "m_accessory",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "name",
          "description"                                => "description",
          "s_str"                                      => "str",
          "s_vit"                                      => "vit",
          "s_int"                                      => "int",
          "s_men"                                      => "men",
          "equip_type"                                 => "equip_type",
          "can_equip_character_level"                  => "can_equip_character_level",
          "m_accessory_rarity_id"                      => "m_accessory_rarity_id",
          "m_unit_skill_1_id"                          => "m_unit_skill_1_id",
          "m_unit_skill_2_id"                          => "m_unit_skill_2_id",
          "m_unit_skill_3_id"                          => "m_unit_skill_3_id",
          "image_resource_name"                        => "image_resource_name",
          "sex_type"                                   => "sex_type",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "m_accessory_rarity",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "name",
          "sell_price"                                 => "sell_price",
          "rarity_num"                                 => "rarity_num",
        ],
      ],
      "saocr_armors" => [
        "gametablename" => "m_armor",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "name",
          "description"                                => "description",
          "m_armor_rarity_id"                          => "m_armor_rarity_id",
          "sex_type"                                   => "sex_type",
          "str_min"                                    => "str_min",
          "str_max"                                    => "str_max",
          "vit_min"                                    => "vit_min",
          "vit_max"                                    => "vit_max",
          "int_min"                                    => "int_min",
          "int_max"                                    => "int_max",
          "men_min"                                    => "men_min",
          "men_max"                                    => "men_max",
          "equip_type"                                 => "equip_type",
          "can_equip_character_level"                  => "can_equip_character_level",
          "image_resource_name"                        => "image_resource_name",
        ],
      ],
      "saocr_armor_rarities" => [
        "gametablename" => "m_armor_rarity",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "name",
          "sell_price"                                 => "sell_price",
          "rarity_num"                                 => "rarity_num",
        ],
      ],
      "saocr_battle_combos" => [
        "gametablename" => "m_battle_combo",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "combo_range_min"                            => "combo_range_min",
          "combo_range_max"                            => "combo_range_max",
          "skill_point"                                => "skill_point",
          "damage_rate"                                => "damage_rate",
          "combo_level"                                => "combo_level",
          "combo_effect_resource_name"                 => "combo_effect_resource_name",
          "combo_broken_effect_resource_name"          => "combo_broken_effect_resource_name",
          " combo_effect_level"                        => "combo_effect_level",
        ],
      ],
      "saocr_character_awake_auras" => [
        "gametablename" => "m_character_awake_aura",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "awaked_num"                                 => "awaked_num",
          "resource_name"                              => "resource_name",
        ],
      ],
      "saocr_character_awake_commons" => [
        "gametablename" => "m_character_awake_common",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "max_awaked_num"                             => "max_awaked_num",
          "max_rarity_num"                             => "max_rarity_num",
        ],
      ],
      "saocr_character_awake_costs" => [
        "gametablename" => "m_character_awake_cost",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "rarity_num"                                 => "rarity_num",
          "awaked_num"                                 => "awaked_num",
          "price"                                      => "price",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
      "saocr_accessory_rarities" => [
        "gametablename" => "",
        "columns"       => [
          // db col name  => csv col name
          "id"                                         => "ID",
          "name"                                       => "",
          "description"                                => "",
        ],
      ],
    ];
  }
}
