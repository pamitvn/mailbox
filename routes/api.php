<?php

use App\Http\Controllers\API\AdminServiceController;
use App\Http\Controllers\API\ServiceController;
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

Route::middleware('auth.api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'controller' => ServiceController::class,
], function () {
    Route::get('services', 'index');
    Route::get('buy-product', 'buyProduct')->middleware('auth.api');
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth.api', 'can:admin'],
    'controller' => AdminServiceController::class,
], function () {
    Route::get('services', 'index');
    Route::get('add-product', 'addProduct');
});
