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
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
//$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'bonus'], function () use ($router) {
        $router->get('', 'Catalogos\WorkerController@index');
        $router->post('/new-bonus', 'Catalogos\WorkerController@newBonus');
        $router->post('/edit-bonus', 'Catalogos\WorkerController@editDetailBonus');
        $router->post('/deactivate-bonus', 'Catalogos\WorkerController@deactivateBonus');
        $router->post('/add-worker', 'Catalogos\WorkerController@addWorkers');
        $router->post('/delete-worker', 'Catalogos\WorkerController@deleteWorker');
    });
    $router->group(['prefix' => 'worker'], function () use ($router) {
        $router->get('','Catalogos\WorkerController@index');
        $router->get('/get/{id}','Catalogos\WorkerController@show');
        $router->post('','Catalogos\WorkerController@store');
        $router->post('/{id}','Catalogos\WorkerController@update');
        $router->delete('/{id}/delete','Catalogos\WorkerController@destroy');
    });
    $router->group(['prefix' => 'payroll'], function () use ($router) {
        $router->get('','Catalogos\PayrollController@index');
        $router->post('/new-payroll','Catalogos\PayrollController@newPayroll');
        $router->get('/get/{id}','Catalogos\PayrollController@showPayroll');
    });
    $router->group(['prefix' => 'detail'], function () use ($router) {
        $router->get('','Catalogos\DetailController@index');
        $router->post('/new-detail','Catalogos\DetailController@newDetail');
        $router->get('/get/{id}','Catalogos\DetailController@showPayroll');
    });
});
