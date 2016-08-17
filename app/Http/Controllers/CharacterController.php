<?php namespace App\Http\Controllers;

use DB;
use Lang;

class CharacterController extends Controller {

  private $_env = 'character';

  public function lang() {
    echo json_encode(Lang::get('character'), JSON_NUMERIC_CHECK);
  }

  public function exploreCK() {
    $_action = 'explore';
    $_viewtype = 'character/explore';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    $charKindIDs = $this->getKindIDs();
    $dataCharKinds = self::getKinds();
    //dd($dataCharKinds);
    $charKinds = [];

    foreach ($charKindIDs as $charkind) {
      $charKindData = [];

      // get the right ck
      foreach ($dataCharKinds as $key => $ckData) {
        if ($charkind->id == $ckData->id) {
          $charKindData['charKind'] = $ckData;
        }
      }
      //$charKindData['charKind'] = $this->getKind($charkind->id)[0];
      $charKindData['char0'] = $this->getNpcCharactersByKind($charkind->id)[0];
      $charKindData['charKindTypes'] = $this->getKindTypes($charkind->id);

      $charKindFormattedData = [
        'id'                 => $charKindData['charKind']->id,
        // 'name'               => $charKindData['charKind']->name,
        'name'               => Lang::get('character.character_kind.'.$charKindData['charKind']->id.'.name'),
        'nameEnglish'        => $charKindData['charKind']->name_english,
        'headName'           => $charKindData['charKind']->head_name,
        'sex'                => $charKindData['charKind']->sex_type,
        'rarityID'           => $charKindData['charKind']->rarity_id,
        'rarityName'         => $charKindData['charKind']->rarity_name,
        'rarityNum'          => $charKindData['charKind']->rarity_num,
        'maxLevel'           => $charKindData['charKind']->max_level,
        'cost'               => $charKindData['char0']->cost,
        'typeID'             => $charKindData['char0']->type_id,
        'typeName'           => $charKindData['char0']->type_name,
        'elementalID'        => $charKindData['char0']->elemental_id,
        'elementalName'      => $charKindData['char0']->elemental_name,
        'weaponCategoryID'   => $charKindData['char0']->weapon_category_id,
        'weaponCategoryName' => $charKindData['char0']->weapon_category_name,
        'types'              => $charKindData['charKindTypes']
      ];

      if (substr(strval($charKindData['charKind']->id), -1) == '6') {
        $charKindFormattedData['imgid'] = $charKindData['charKind']->id - 16;
      } else {
        $charKindFormattedData['imgid'] = $charKindData['charKind']->id;
      }

      $charKinds[] = $charKindFormattedData;
    }
    //var_dump($charKinds[0]);

    

    $_viewdata['char_kinds_json'] = json_encode($charKinds);
    $_viewdata['char_rarities']   = json_encode($this->getRaritiesHasChar());
    $_viewdata['char_teamcost']   = json_encode($this->getTeamCosts());


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

  public function exploreRecipes() {
    $_action = 'explorecharrecipes';
    $_viewtype = 'character/explorerecipes';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    return view($_viewtype, $_viewdata);
  }

  public function exploreExpChart() {
    $_action = 'explorecharcharts';
    $_viewtype = 'character/exploreexpcharts';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    $expchartIDs = $this->getExpChartIDs();
    $expcharts   = [];

    foreach ($expchartIDs as $ec) {
      $expcharts[$ec->exp_pattern_id] = $this->getExpChartByID($ec->exp_pattern_id);
    }

    $_viewdata['expcharts'] = $expcharts;
    //dd($expcharts[1]);
    return view($_viewtype, $_viewdata);
  }

  public function getCK($charID, $name = null) {
    $_action = 'show';
    $_viewtype = 'character/show';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action,
      'char_kind_id'    => $charID
      );

    if ($ckModel = $this->getKind($charID)) {
      $char_kind = $ckModel[0];

      if (substr(strval($char_kind->id), -1) == '6') {
        $_viewdata['char_kind_imgid'] = $char_kind->id - 16;
      } else {
        $_viewdata['char_kind_imgid'] = $char_kind->id;
      }

      $_viewdata['char_kind'] = $char_kind;

      $_viewdata['chars'] = $this->getNpcCharactersByKind($charID);
      $_viewdata['char_kind_types'] = $this->getKindTypes($charID);

      /**
       * Awakening Block
       */
      $hasAwake  = null;
      $isAwakeOf = null;

      $afterChar  = null;
      $beforeChar = null;

      if ($hasAwake  = $this->getAwakeRelation($char_kind->id) ? $this->getAwakeRelation($char_kind->id)[0] : null) {
        $afterChar = $this->getKind($hasAwake->m_after_character_kind_id)[0];
      }
      if ($isAwakeOf = $this->getAwakeRelation(null, $char_kind->id) ? $this->getAwakeRelation(null, $char_kind->id)[0] : null) {
        $beforeChar = $this->getKind($isAwakeOf->m_character_kind_id)[0];
      }

      $_viewdata['char_after_awake']  = $afterChar;
      $_viewdata['char_before_awake'] = $beforeChar;
    } else {
      $_viewtype = 'errors/404';
    }

    //var_dump($beforeChar);
    //var_dump($_viewdata);
    return view($_viewtype, $_viewdata);
  }



  /**
   * DB functions
   */
  public function getNpcCharacters() {
    return DB::table('saocr_npc_characters')
      ->leftJoin(
        'saocr_npc_character_types',
          'saocr_npc_characters.m_npc_character_type_id', '=', 'saocr_npc_character_types.id')
      ->leftJoin(
        'saocr_elementals',
          'saocr_npc_characters.m_elemental_id', '=', 'saocr_elementals.id')
      ->leftJoin(
        'saocr_weapon_categories',
          'saocr_npc_characters.m_weapon_category_id', '=', 'saocr_weapon_categories.id')
      //->where('saocr_npc_characters.m_character_kind_id', '=', $characterID)
      ->where('saocr_npc_characters.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_npc_characters.id',
        'saocr_npc_characters.hp_min',
        'saocr_npc_characters.hp_max',
        'saocr_npc_characters.str_min',
        'saocr_npc_characters.str_max',
        'saocr_npc_characters.vit_min',
        'saocr_npc_characters.vit_max',
        'saocr_npc_characters.int_min',
        'saocr_npc_characters.int_max',
        'saocr_npc_characters.men_min',
        'saocr_npc_characters.men_max',
        'saocr_npc_characters.cost',
        'saocr_npc_character_types.id as type_id',
        'saocr_npc_character_types.name as type_name',
        'saocr_elementals.id as elemental_id',
        'saocr_elementals.name as elemental_name',
        'saocr_weapon_categories.id as weapon_category_id',
        'saocr_weapon_categories.name as weapon_category_name')
      ->get();
  }
  public function getNpcCharactersByKind($characterID) {
    return DB::table('saocr_npc_characters')
      ->leftJoin(
        'saocr_npc_character_types',
          'saocr_npc_characters.m_npc_character_type_id', '=', 'saocr_npc_character_types.id')
      ->leftJoin(
        'saocr_elementals',
          'saocr_npc_characters.m_elemental_id', '=', 'saocr_elementals.id')
      ->leftJoin(
        'saocr_weapon_categories',
          'saocr_npc_characters.m_weapon_category_id', '=', 'saocr_weapon_categories.id')
      ->where('saocr_npc_characters.m_character_kind_id', '=', $characterID)
      ->where('saocr_npc_characters.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_npc_characters.hp_min',
        'saocr_npc_characters.hp_max',
        'saocr_npc_characters.str_min',
        'saocr_npc_characters.str_max',
        'saocr_npc_characters.vit_min',
        'saocr_npc_characters.vit_max',
        'saocr_npc_characters.int_min',
        'saocr_npc_characters.int_max',
        'saocr_npc_characters.men_min',
        'saocr_npc_characters.men_max',
        'saocr_npc_characters.cost',
        'saocr_npc_character_types.id as type_id',
        'saocr_npc_character_types.name as type_name',
        'saocr_elementals.id as elemental_id',
        'saocr_elementals.name as elemental_name',
        'saocr_weapon_categories.id as weapon_category_id',
        'saocr_weapon_categories.name as weapon_category_name')
      ->get();
  }

  static public function getKinds() {
    return DB::table('saocr_character_kinds')
      ->leftJoin(
        'saocr_character_rarities',
          'saocr_character_kinds.m_rarity_id', '=', 'saocr_character_rarities.id')
      ->where('saocr_character_kinds.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_character_kinds.id',
        'saocr_character_kinds.name',
        'saocr_character_kinds.name_english',
        'saocr_character_kinds.head_name',
        'saocr_character_kinds.sex_type',
        'saocr_character_kinds.avatar_resource_name',
        'saocr_character_rarities.id as rarity_id',
        'saocr_character_rarities.name as rarity_name',
        'saocr_character_rarities.rarity_num as rarity_num',
        'saocr_character_rarities.max_level',
        'saocr_character_rarities.sell_price',
        'saocr_character_rarities.sword_skill_power_rate')
      ->get();
  }
  public function getKind($characterID) {
    return DB::table('saocr_character_kinds')
      ->leftJoin(
        'saocr_character_rarities',
          'saocr_character_kinds.m_rarity_id', '=', 'saocr_character_rarities.id')
      ->where('saocr_character_kinds.id', '=', $characterID)
      ->where('saocr_character_kinds.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_character_kinds.id',
        'saocr_character_kinds.name',
        'saocr_character_kinds.name_english',
        'saocr_character_kinds.head_name',
        'saocr_character_kinds.sex_type',
        'saocr_character_kinds.avatar_resource_name',
        'saocr_character_rarities.id as rarity_id',
        'saocr_character_rarities.name as rarity_name',
        'saocr_character_rarities.rarity_num as rarity_num',
        'saocr_character_rarities.max_level',
        'saocr_character_rarities.sell_price',
        'saocr_character_rarities.sword_skill_power_rate')
      ->get();
  }

  public function getKindIDs() {
    return DB::table('saocr_character_kinds')
      ->leftJoin(
        'saocr_character_rarities',
          'saocr_character_kinds.m_rarity_id', '=', 'saocr_character_rarities.id')
      ->where('saocr_character_kinds.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_character_kinds.id')
      ->get();
  }

  public function getKindTypes($characterID) {
    return DB::table('saocr_npc_characters')
      ->leftJoin(
        'saocr_npc_character_types',
          'saocr_npc_characters.m_npc_character_type_id', '=', 'saocr_npc_character_types.id')
      ->where('saocr_npc_characters.m_character_kind_id', '=', $characterID)
      ->where('saocr_npc_characters.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_npc_character_types.id as type_id',
        'saocr_npc_character_types.name as type_name')
      ->get();
  }

  public function getAwakeRelation($beforeCharacterID = null, $afterCharacterID = null) {
    if ($beforeCharacterID) {
      return DB::table('saocr_character_awake_receipts')
        ->where('saocr_character_awake_receipts.m_character_kind_id', '=', $beforeCharacterID)
        ->where('saocr_character_awake_receipts.t_version_id', '=', env('DB_VERSION_ID'))
        ->select(
          'saocr_character_awake_receipts.id',
          'saocr_character_awake_receipts.m_character_kind_id',
          'saocr_character_awake_receipts.m_after_character_kind_id',
          'saocr_character_awake_receipts.start_at',
          'saocr_character_awake_receipts.end_at')
        ->get();
    }
    if ($afterCharacterID) {
      return DB::table('saocr_character_awake_receipts')
        ->where('saocr_character_awake_receipts.m_after_character_kind_id', '=', $afterCharacterID)
        ->where('saocr_character_awake_receipts.t_version_id', '=', env('DB_VERSION_ID'))
        ->select(
          'saocr_character_awake_receipts.id',
          'saocr_character_awake_receipts.m_character_kind_id',
          'saocr_character_awake_receipts.m_after_character_kind_id',
          'saocr_character_awake_receipts.start_at',
          'saocr_character_awake_receipts.end_at')
        ->get();
    }
  }

  public function getRarities() {
    return DB::table('saocr_character_rarities')
      ->orderBy('rarity_num', 'asc')
      ->groupBy('rarity_num')
      ->where('saocr_character_rarities.t_version_id', '=', env('DB_VERSION_ID'))
      ->select('rarity_num')
      ->get();
  }
  public function getRaritiesHasChar() {
    return DB::table('saocr_character_kinds')
      ->leftJoin(
        'saocr_character_rarities',
          'saocr_character_kinds.m_rarity_id', '=', 'saocr_character_rarities.id')
      ->orderBy('saocr_character_rarities.rarity_num', 'asc')
      ->groupBy('saocr_character_rarities.rarity_num')
      ->where('saocr_character_kinds.t_version_id', '=', env('DB_VERSION_ID'))
      ->select('saocr_character_rarities.rarity_num as rarity_num')
      ->get();
  }
  public function getTeamCosts() {
    return DB::table('saocr_npc_characters')
      ->orderBy('saocr_npc_characters.cost', 'asc')
      ->groupBy('saocr_npc_characters.cost')
      ->where('saocr_npc_characters.t_version_id', '=', env('DB_VERSION_ID'))
      ->select('saocr_npc_characters.cost as teamcost')
      ->get();
  }

  public function getExpChartIDs() {
    return DB::table('saocr_npc_character_required_experiences')
      ->orderBy('saocr_npc_character_required_experiences.m_exp_pattern_id', 'asc')
      ->groupBy('saocr_npc_character_required_experiences.m_exp_pattern_id')
      ->where('saocr_npc_character_required_experiences.t_version_id', '=', env('DB_VERSION_ID'))
      ->select('saocr_npc_character_required_experiences.m_exp_pattern_id as exp_pattern_id')
      ->get();
  }
  public function getExpChartByID($expchartID) {
    return DB::table('saocr_npc_character_required_experiences')
      ->orderBy('saocr_npc_character_required_experiences.character_level', 'asc')
      ->where('saocr_npc_character_required_experiences.m_exp_pattern_id', '=', $expchartID)
      ->where('saocr_npc_character_required_experiences.t_version_id', '=', env('DB_VERSION_ID'))
      ->select(
        'saocr_npc_character_required_experiences.character_level',
        'saocr_npc_character_required_experiences.required_exp')
      ->get();
  }
}