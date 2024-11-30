<?php

use Illuminate\Http\Request;
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

Route::group(['namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1', 'namespace' => 'v1'], function () {
        require(__DIR__ . '/API/v1/api.php');
    });
});

Route::group(['prefix' => 'v1/admin'], function () {
    require(__DIR__ . '/API/v1/admin.php');
});