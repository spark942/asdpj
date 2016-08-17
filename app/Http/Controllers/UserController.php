<?php namespace App\Http\Controllers;

use Auth;
use Request;
use Redirect;

class UserController extends Controller {
  private $_env = 'user';



  public function signOut(){
    $_action   = 'signout';
    $_viewtype = 'user/signin';
    $_viewdata = array(
      'env'        => $this->_env,
      'action'     => $_action
      );

    Auth::logout();
    return view($_viewtype, $_viewdata);
  }

  public function users(){

  }

  public function myprofile(){
    $_action   = 'myprofile';
    $_viewtype = 'user/self';
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

  public function books($uid){
    $carnet = Carnet::where('user_id', '=', $user_id);
    return $carnet;
  }
}
