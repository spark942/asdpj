<?php namespace App\Http\Controllers;

use DB;
use View;
use Lang;

class WeaponController extends Controller {

  private $_env = 'character';

  public function lang($name) {
    echo json_encode(Lang::get('saocr_weapon_categories'));
  }

  public function explore() {
    $_action = 'explore';
    $_viewtype = 'character/explore';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    $charKindIDs = $this->getKindIDs();
    $charKinds = [];

    foreach ($charKindIDs as $charkind) {
      $charKindData = [];

      $charKindData['charKind'] = $this->getKind($charkind->id)[0];
      $charKindData['char0'] = $this->getNpcCharactersByKind($charkind->id)[0];
      $charKindData['charKindTypes'] = $this->getKindTypes($charkind->id);

      $charKindFormattedData = [
        'id'                 => $charKindData['charKind']->id,
        'name'               => $charKindData['charKind']->name,
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

    $_viewdata['weapon_categories_localization'] = json_encode(Lang::get('saocr_weapon_categories'));

    return View::make($_viewtype, $_viewdata);
  }

  public function exploreRecipes() {

  }

  public function get($charID, $name = null) {
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
    return View::make($_viewtype, $_viewdata);
  }

  /**
   * DB functions
   */

  public function getRarities() {
    return DB::table('saocr_character_rarities')
      ->orderBy('rarity_num', 'asc')
      ->groupBy('rarity_num')
      ->select('rarity_num')
      ->get();
  }
}