<?php

use App\Actions;
use App\Http\Controllers\Account;
use App\Http\Controllers\Admin;
use App\Http\Controllers\RechargeController;
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
    'middleware' => ['user.not.blacklisted'],
], function () {
    Route::get('/', [StaticPageController::class, 'home']);

    Route::group([
        'middleware' => ['auth'],
    ], function () {
        Route::post('buy-product', Actions\BuyProductAction::class)->name('product.buy');
        Route::post('export-product', Actions\ExportProductAction::class)->name('product.export');

        Route::group([
            'prefix' => 'account',
            'as' => 'account.',
        ], function () {
            Route::group([
                'prefix' => 'profile',
                'controller' => Account\ProfileController::class,
            ], function () {
                Route::get('/', 'index')->name('profile');
                Route::put('/', 'update');
            });

            Route::group([
                'prefix' => 'api',
                'controller' => Account\APIManagerController::class,
            ], function () {
                Route::get('/', 'index')->name('api');
                Route::post('/', 'store');
            });

            Route::group([
                'prefix' => 'reset-password',
                'controller' => Account\ResetPasswordController::class,
            ], function () {
                Route::get('/', 'index')->name('reset-password');
                Route::put('/', 'update');
            });
        });

        Route::get('recharge', [RechargeController::class, 'index'])->name('recharge');
        Route::get('orders', \App\Http\Controllers\OrderController::class)->name('orders');
        Route::get('statistic', [StaticPageController::class, 'statistic'])->name('statistic');

        Route::group([
            'prefix' => 'admin',
            'as' => 'admin.',
            'middleware' => ['can:admin'],
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
                'controller' => Admin\SettingManagerController::class,
            ], function () {
                Route::get('{setting?}', 'index')->name('setting');
                Route::post('{setting?}', 'update');
            });

            /**
             * Manager Users
             */
            Route::resource('users', Admin\UserManagerController::class, [
                'names' => 'user',
                'except' => ['show'],
            ]);
            Route::get('users/{user}/balance', [Admin\UserManagerController::class, 'balance'])->name('user.balance');
            Route::post('users/{user}/balance', [Admin\UserManagerController::class, 'storeBalance']);

            /**
             * Manager User Blacklisted
             */
            Route::resource('user-blacklisted', Admin\UserBlacklistedManagerController::class, [
                'names' => 'blacklisted.user',
                'except' => ['show'],
                'blacklisted_user' => 'user_blacklisted',
            ]);

            /**
             * Manager Banks
             */
            Route::resource('banks', Admin\BankManagerController::class, [
                'names' => 'bank',
                'except' => ['show'],
            ]);

            /**
             * Manager Recharge Histories
             */
            Route::resource('recharge-histories', Admin\RechargeHistoryManagerController::class, [
                'names' => 'recharge-history',
                'only' => ['index', 'destroy'],
            ]);

            /**
             * Manager Services
             */
            $servicePrefix = 'services';

            Route::group([
                'prefix' => $servicePrefix,
                'controller' => Admin\ProductManagerController::class,
            ], function () {
                Route::post('products/bulk-destroy', 'bulkDestroy')->name('service.product.bulk-destroy');
                Route::post('{service}/orders/bulk-destroy', [Admin\ServiceManagerController::class, 'bulkDestroy'])->name('service.order.bulk-destroy');
                Route::resource('products', Admin\ProductManagerController::class, [
                    'names' => 'service.product',
                    'only' => ['index', 'store', 'destroy'],
                ]);
                Route::post('{service}', [Admin\ServiceManagerController::class, 'update'])->name('service.update');
            });

            Route::resource($servicePrefix, Admin\ServiceManagerController::class, [
                'names' => 'service',
                'except' => ['update'],
            ]);

            Route::get('statistics', Admin\StatisticController::class)->name('statistics');
        });
    });
});

Route::group([
    'prefix' => 'auth',
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

Route::get('test', [StaticPageController::class, 'test']);
