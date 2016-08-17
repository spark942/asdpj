<?php namespace App\Http\Controllers;

use Lang;
use File;

class GamejsonController extends Controller {

  private $_env = 'gamejson';

  public function lang() {
    echo json_encode(Lang::get('character'), JSON_NUMERIC_CHECK);
  }

  public static function unitBook() {
    $unitBookVersion = '2_13';
    $unitBookPath = storage_path("json/unitbook_v" . $unitBookVersion . ".json");
    $file = File::get($unitBookPath);
    $file = preg_replace("/[\r\n]+/", " ", $file);

    return json_decode($file, true);
  }

  public static function textBook() {
    $textBookVersion = '2_13';
    $textBookPath = storage_path("json/textBook.json");
    $file = File::get($textBookPath);
    $file = preg_replace("/[\r\n]+/", " ", $file);

    return json_decode($file, true);
  }

}