<?php

use App\Http\Controllers\{Admin, Account};
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

Route::group([
    'middleware' => ['user.not.blacklisted']
], function () {

    Route::get('/', [StaticPageController::class, 'home']);

    Route::group([
        'middleware' => ['auth']
    ], function () {

        Route::group([
            'prefix' => 'account',
            'as' => 'account.'
        ], function () {

            Route::group([
                'prefix' => 'profile',
                'controller' => Account\ProfileController::class,
            ], function () {
                Route::get('', 'index')->name('profile');
                Route::put('', 'update');
            });

            Route::group([
                'prefix' => 'api',
                'controller' => Account\APIManagerController::class,
            ], function () {
                Route::get('', 'index')->name('api');
                Route::post('', 'store');
            });

            Route::group([
                'prefix' => 'reset-password',
                'controller' => Account\ResetPasswordController::class,
            ], function () {
                Route::get('reset-password', 'index')->name('reset-password');
                Route::put('reset-password', 'update');
            });

        });

        Route::group([
            'prefix' => 'admin',
            'as' => 'admin.',
            'middleware' => ['can:admin']
        ], function () {

            /**
             * Dynamic do-action
             */
            Route::any('handle-action', Admin\DoActionController::class)->name('handle-action');

            /**
             * Manager Dynamic Settings
             */
            Route::group([
                'prefix' => 'settings',
                'controller' => Admin\SettingManagerController::class
            ], function () {
                Route::get('{setting?}', 'index')->name('setting');
                Route::post('{setting?}', 'update');
            });

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
             * Manager User Blacklisted
             */
            Route::resource('user-blacklisted', Admin\UserBlacklistedManagerController::class, [
                'names' => 'blacklisted.user',
                'except' => ['show'],
                'blacklisted_user' => 'user_blacklisted'
            ]);

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

            /**
             * Manager Services
             */
            $servicePrefix = 'services';

            Route::group([
                'prefix' => $servicePrefix,
            ], function () {
                Route::resource('products', Admin\ProductManagerController::class, [
                    'names' => 'service.product',
                    'only' => ['index', 'store', 'destroy']
                ]);
                Route::post("{service}", [Admin\ServiceManagerController::class, 'update'])->name('service.update');
            });

            Route::resource($servicePrefix, Admin\ServiceManagerController::class, [
                'names' => 'service',
                'except' => ['update'],
            ]);
        });

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
