<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1',
], function ($router) {
    $router->group([], function ($router) {
        // Github api routes
        $router->group([
            'prefix' => 'github/repos/',
        ], function ($router) {
            $router->get('', 'GitHubController@listRepositories')->name('github.repos');
        });
    });
});
