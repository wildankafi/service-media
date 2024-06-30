<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    // return $router->app->version();
    return view('index');
});
// $router->get('/key', function() {
//     $he =\Illuminate\Support\Str::random(32);
//     $key = uniqid(true).''.$he;
//     return $key;
// });
$router->POST('/media','MediaController@create');
$router->GET('/media','MediaController@getAll');
$router->GET('/media/{id}','MediaController@getById');
$router->POST('/media/app','MediaController@getByApp');
$router->POST('/media/data','MediaController@getByData');
$router->DELETE('/media/{id}','MediaController@destroy');

require __DIR__.'/version/v1.php';
