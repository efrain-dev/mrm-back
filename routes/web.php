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
$router->get('/logo', function () use ($router) {
    $path    =   app()->basePath('public') . '/asset/Logo.png';
    return $path;
});
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'LumenAuthController@login');
    $router->post('logout', 'LumenAuthController@logout');
    $router->post('refresh', 'LumenAuthController@refresh');
});
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'bonus'], function () use ($router) {
        $router->get('/get/{type}', 'Catalogos\BonusController@index');
        $router->get('/get-bonus', 'Catalogos\BonusController@getBonus');
        $router->get('/type', 'Catalogos\BonusController@getType');
        $router->post('', 'Catalogos\BonusController@newBonus');
        $router->post('/edit', 'Catalogos\BonusController@editDetailBonus');
        $router->delete('/{id}/delete', 'Catalogos\BonusController@deleteBonus');
    });
    $router->group(['prefix' => 'worker'], function () use ($router) {
        $router->get('', 'Catalogos\WorkerController@index');
        $router->get('/get/{id}', 'Catalogos\WorkerController@show');
        $router->post('', 'Catalogos\WorkerController@store');
        $router->put('/{id}', 'Catalogos\WorkerController@update');
        $router->delete('/{id}/delete', 'Catalogos\WorkerController@destroy');
        $router->get('/send-link', 'ReportController@sendLinks');
        $router->get('/pay-worker', 'ReportController@getPFDWorker');

    });
    $router->group(['prefix' => 'payroll'], function () use ($router) {
        $router->get('', 'Catalogos\PayrollController@index');
        $router->get('/get-order', 'Catalogos\PayrollController@getOrder');
        $router->put('/status/{id}', 'Catalogos\PayrollController@changeStatus');
        $router->post('', 'Catalogos\PayrollController@newPayroll');
        $router->get('/get', 'Catalogos\PayrollController@showPayroll');
        $router->get('/year', 'Catalogos\PayrollController@getPayrollsApi');
        $router->get('/worker', 'Catalogos\PayrollController@getWorkerApi');
        $router->get('/pdf', 'ReportController@getPDFPayroll');
    });
    $router->group(['prefix' => 'detail'], function () use ($router) {
        $router->get('', 'Catalogos\DetailController@getReport');
        $router->post('', 'Catalogos\DetailController@newDetail');
        $router->get('/get', 'Catalogos\DetailController@getReport');
        $router->delete('/{id}/delete', 'Catalogos\DetailController@deleteReport');

    });
    $router->group(['prefix' => 'link'], function () use ($router) {
        $router->get('', 'Catalogos\LinkController@index');
        $router->get('/get/{id}', 'Catalogos\LinkController@show');
        $router->post('', 'Catalogos\LinkController@store');
        $router->put('/{id}', 'Catalogos\LinkController@update');
        $router->delete('/{id}/delete', 'Catalogos\LinkController@destroy');
    });
    $router->group(['prefix' => 'config'], function () use ($router) {
        $router->get('', 'Catalogos\ConfigController@index');
        $router->put('', 'Catalogos\ConfigController@update');
    });

});


