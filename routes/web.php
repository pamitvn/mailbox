<?php

use App\Actions;
use App\Http\Controllers\Account;
use App\Http\Controllers\Admin;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\Storages;
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
    Route::get('recent-orders', Actions\GetRecentOrderAction::class)->name('recent-orders');

    Route::group([
        'middleware' => ['auth'],
    ], function () {
        Route::get('statistic', [StaticPageController::class, 'statistic'])->name('statistic');
        Route::get('recharge', [RechargeController::class, 'index'])->name('recharge');
        Route::get('orders', \App\Http\Controllers\OrderController::class)->name('orders');

        Route::post('buy-product', Actions\BuyProductAction::class)->name('product.buy');
        Route::post('export-product', Actions\ExportProductAction::class)->name('product.export');

        /**
         * Manager storages
         */
        Route::resource('storages', Storages\StorageController::class, [
            'names' => 'storage',
            'middleware' => 'can:storage',
            'except' => ['show'],
        ]);
        Route::post('storages/{storage}/exports', Storages\StorageExportController::class)
            ->middleware('can:storage')
            ->name('storage.export');

        /**
         * Manager storage containers
         */
        Route::resource('storages.containers', Storages\ContainerController::class, [
            'names' => 'storage.container',
            'middleware' => 'can:storage',
            'only' => ['index', 'store', 'destroy'],
        ]);
        Route::group([
            'prefix' => 'storages/{storage}/containers',
            'as' => 'storages.container.',
        ], function () {
            Route::post('bulk-destroy', Storages\StorageContainerBulkDestroyController::class)->name('bulk-destroy');
            Route::post('bulk-export', Storages\StorageContainerExportController::class)->name('bulk-export');
        });

        /**
         * Manager Account
         */
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
                'controller' => Account\APIController::class,
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

        /**
         * Admin Areas
         */
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
                'controller' => Admin\SettingController::class,
            ], function () {
                Route::get('{setting?}', 'index')->name('setting');
                Route::post('{setting?}', 'update');
            });

            /**
             * Manager Users
             */
            Route::resource('users', Admin\Users\UserController::class, [
                'names' => 'user',
                'except' => ['show'],
            ]);
            Route::get('users/{user}/balance', [Admin\Users\UserBalanceController::class, 'index'])->name('user.balance');
            Route::post('users/{user}/balance', [Admin\Users\UserBalanceController::class, 'update']);

            /**
             * Manager User Blacklisted
             */
            Route::resource('user-blacklisted', Admin\UserBlacklistedController::class, [
                'names' => 'blacklisted.user',
                'except' => ['show'],
                'blacklisted_user' => 'user_blacklisted',
            ]);

            /**
             * Manager Banks
             */
            Route::resource('banks', Admin\BankController::class, [
                'names' => 'bank',
                'except' => ['show'],
            ]);

            /**
             * Manager Recharge Histories
             */
            Route::resource('recharge-histories', Admin\RechargeHistoryController::class, [
                'names' => 'recharge-history',
                'only' => ['index', 'destroy'],
            ]);

            /**
             * Manager Services
             */
            Route::resource('services', Admin\Services\ServiceController::class, [
                'names' => 'service',
            ]);
            Route::get('services/{service}/permission', [Admin\Services\ServicePermissionController::class, 'index'])->name('service.permission');
            Route::post('services/{service}/permission', [Admin\Services\ServicePermissionController::class, 'update']);
            Route::get('services/{service}/user-purchased', Admin\Services\ServiceUserPurchasedController::class)
                ->name('service.user-purchased');

            /**
             * Manager Products
             */
            Route::resource('services.products', Admin\Products\ProductController::class, [
                'names' => 'service.product',
                'only' => ['index', 'store', 'destroy'],
            ]);
            Route::post('products/bulk-destroy', Admin\Products\BulkDestroyController::class)
                ->name('service.product.bulk-destroy');

            /**
             * Statistics
             */
            Route::get('statistics', Admin\StatisticController::class)->name('statistics');

            /**
             * Manager Orders
             */
            Route::resource('orders', Admin\OrderController::class, [
                'names' => 'order',
                'only' => ['index', 'destroy'],
            ]);
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
