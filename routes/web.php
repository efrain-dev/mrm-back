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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'LumenAuthController@login');
    $router->post('logout', 'LumenAuthController@logout');
    $router->post('refresh', 'LumenAuthController@refresh');
    $router->post('me', 'LumenAuthController@me');
    $router->get('/check', ['middleware' => 'auth', function () use ($router) {
        return 'Tiene permiso';
    }]);

});



$router->group(['prefix' => 'api','middleware' => 'auth'], function () use ($router) {
        $router->group(['prefix' => 'worker'], function () use ($router) {
            $router->get('','Catalogos\WorkerController@index');
            $router->get('/get/{id}','Catalogos\WorkerController@show');
            $router->post('','Catalogos\WorkerController@store');
            $router->put('/{id}','Catalogos\WorkerController@update');
            $router->delete('/{id}/delete','Catalogos\WorkerController@destroy');
        });
});
