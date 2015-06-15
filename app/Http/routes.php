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

/* Default homepage */

$app->get('/', function() {
    return view('desktop.main');
});

/* GET API */

$app->get('/api/entries', 'App\Http\Controllers\Signups\ReadController@getAll');
$app->get('/api/entries/{id_game}', 'App\Http\Controllers\Signups\ReadController@getOne');
$app->get('/api/games', 'App\Http\Controllers\Signups\ReadController@getGames');

/* POST API */

$app->post('/api/entries/create/{id_game}', 'App\Http\Controllers\Signups\EntryAPIController@submit');
$app->post('/api/entries/approve/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@approvePass');
$app->post('/api/entries/edit/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@edit');
$app->post('/api/entries/delete/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@deletePass');
$app->get('/api/entries/forgot/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@forgot');

/* Admin API */
/* TO DO: Wrap with Middleware */

$app->post('/api/games/create', 'App\Http\Controllers\Signups\AdminController@createGame');
$app->post('/api/games/update', 'App\Http\Controllers\Signups\AdminController@updateGame');
$app->post('/api/games/delete', 'App\Http\Controllers\Signups\AdminController@deleteGame');
$app->post('/api/games/purge', 'App\Http\Controllers\Signups\AdminController@purgeGames');

/* Rendered views */

$app->get('/light', 'App\Http\Controllers\Signups\LightController@getGames');
$app->get('/light/{id_game}', 'App\Http\Controllers\Signups\LightController@getOne');
$app->get('/light/create/{id_game}', 'App\Http\Controllers\Signups\LightController@getCreateForm');
$app->get('/light/edit/{id_entry}', 'App\Http\Controllers\Signups\LightController@getEditForm');
$app->get('/light/delete/{id_entry}', 'App\Http\Controllers\Signups\LightController@getDeleteForm');
$app->get('/light/delete/{id_entry}/{crpt_pass}', 'App\Http\Controllers\Signups\EntryAPIController@delete');
$app->get('/light/verify/{id_entry}', 'App\Http\Controllers\Signups\LightController@getVerifyForm');
$app->get('/light/verify/{id_entry}/{crpt_pass}', 'App\Http\Controllers\Signups\EntryAPIController@verify');
$app->get('/light/forgot/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@forgot');

/* Validators */

$app->post('/light/create/{id_game}', 'App\Http\Controllers\Signups\EntryAPIController@submit');
$app->post('/light/edit/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@edit');
$app->post('/light/delete/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@deletePass');
$app->post('/light/verify/{id_entry}', 'App\Http\Controllers\Signups\EntryAPIController@verifyPass');