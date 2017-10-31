<?php

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

$router->post('/addOneDY', ['uses'=>'Index\IndexController@addOneDy']);
$router->get('/test', ['uses'=>'Index\IndexController@test']);

$router->get('/list', ['uses'=>'Index\IndexController@dataList']);
$router->get('/detail/{id}', ['uses'=>'Index\IndexController@dataDetail']);
$router->get('/edit', ['uses'=>'Index\IndexController@dataEdit']);
