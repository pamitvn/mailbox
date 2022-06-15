<?php

use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\{Admin};
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
    'middleware' => ['auth']
], function () {

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => ['can:admin']
    ], function () {

        /**
         * Manager Users
         */
        Route::resource('users', Admin\UserManagerController::class, [
            'names' => 'user',
            'except' => ['show']
        ]);
        Route::get('users/{user}/balance', [Admin\UserManagerController::class, 'balance'])->name('user.balance');
        Route::post('users/{user}/balance', [Admin\UserManagerController::class, 'storeBalance']);

        /**
         * Manager Banks
         */
        Route::resource('banks', Admin\BankManagerController::class, [
            'names' => 'bank',
            'except' => ['show']
        ]);

        /**
         * Manager Recharge Histories
         */
        Route::resource('recharge-histories', Admin\RechargeHistoryManagerController::class, [
            'names' => 'recharge-history',
            'only' => ['index', 'destroy']
        ]);
    });

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
