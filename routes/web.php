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
    return $router->app->version();
});
$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});
$router->POST('/media','MediaController@create');
$router->GET('/media','MediaController@getAll');
$router->GET('/media/{id}','MediaController@getById');
$router->POST('/media/app','MediaController@getByApp');
$router->POST('/media/data','MediaController@getByData');
$router->POST('/media/test','MediaController@test');
$router->DELETE('/media/{id}','MediaController@destroy');