<?php

use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [StaticPageController::class, 'home']);

Route::group([

], function () {

});

Route::group([
    'prefix' => 'auth'
], function () {
    Auth::routes([
        'login' => true,
        'register' => true,
        'logout' => true,
        'reset' => true,
        'verify' => false,
        'confirm' => false,
    ]);
});
