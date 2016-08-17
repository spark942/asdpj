<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get("/","HomeController@showWelcome");


Route::get("/tot","ToweroftrialController@currentTot");
Route::get("/tot/{roundid}","ToweroftrialController@totround");

// Route::get("/characters","CharacterController@exploreCK");
// Route::group(array('prefix' => 'ck'), function(){
// 	Route::get('{ckid}/{name?}', 'CharacterController@getCK');
// });
// Route::get("/characterexpcharts","CharacterController@exploreExpChart");

// Route::get("/skills","SkillController@explore");
// Route::get("/leaderskills","SkillController@exploreLS");
// Route::group(array('prefix' => 'ls'), function(){
// 	Route::get('{lsid}/{name?}', 'SkillController@get');
// });

// Route::get("/quests/{type?}","QuestController@explore");
// Route::get("/quests/normal","QuestController@exploreNQ");
// Route::get("/quests/extra","QuestController@exploreWQ");
// Route::get("/quests/event","QuestController@exploreEQ");
// Route::group(array('prefix' => 'ls'), function(){
// 	Route::get('{qid}/{name?}', 'QuestController@get');
// });


// Route::post("/lang/character","CharacterController@lang");
// Route::post("/lang/weapon/{name}","WeaponController@lang");



/**
 * USERs
 */

/*Route::any("/signin","UserController@signIn");
Route::any("/signup","UserController@signUp");
Route::any("/signout","UserController@signOut");
*/
Route::get("/me","UserController@myprofile");

Route::group(array("before"=>"auth"), function(){
    Route::get('/home', function()
    {
        return Redirect::to('me/');
    });

    Route::post("/ajax/newsolution","ToweroftrialController@newsolution");
    Route::post("/ajax/votesolution","ToweroftrialController@votesolution");


    Route::get("/admin","AdminController@dashboard");

    /**
     * Admin table ajaxs
     */
	Route::post("/dbtable","AdminController@ajaxDbtable");
	Route::post("/datasavefile","AdminController@ajaxDataSaveFile");

	Route::post("/loadfilelist","AdminController@getDataFileList");
	Route::post("/updatefileusedbytable","AdminController@ajaxUpdateFileUsedByTable");
	Route::post("/getfileusedbytable","AdminController@ajaxGetFileUsedByTable");
});




Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);