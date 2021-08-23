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

$router->get('/', function() {
    return view('index');
});

$router->get('/developers', 'DevelopersController@index');
$router->get('/developers/{id}', 'DevelopersController@show');
$router->post('/developers', 'DevelopersController@store');
$router->put('/developers/{id}', 'DevelopersController@update');
$router->delete('/developers/{id}', 'DevelopersController@delete');
$router->delete('/developers', 'DevelopersController@deleteMultiple');
