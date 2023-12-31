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

    // auth routes
    $router->group([
        'prefix' => 'auth',
    ], function ($router) {
        $router->post('/login', 'AuthController@login');
    });

    $router->group([
        'middleware' => 'auth.api'
    ], function ($router) {
        // Github api routes
        $router->group([
            'prefix' => 'github/repos/',
        ], function ($router) {
            $router->get('', 'GitHubController@listRepositories');
            $router->get('{owner}/{repo}', 'GitHubController@selectRepository');
            $router->get('{owner}/{repo}/pulls/{from?}', 'GitHubController@countReviewsAndPullRequests');
        });
    });
});
