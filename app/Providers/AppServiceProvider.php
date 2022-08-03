<?php

namespace App\Providers;

use App\Charts;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Storage;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\ServiceObserver;
use App\Observers\UserObserver;
use App\PAM\AdminSetting;
use App\PAM\ApiResponse;
use App\PAM\ParentManager;
use ConsoleTVs\Charts\Registrar as RegistrarCharts;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ApiResponse::$getFacadeAccessor, fn () => new ApiResponse());
        $this->app->singleton(AdminSetting::$getFacadeAccessor, fn (Application $app) => new AdminSetting($app->make('config')->get('admin.settings')));
        $this->app->singleton(ParentManager::$getFacadeAccessor, fn () => new ParentManager);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(RegistrarCharts $charts)
    {
        $this->defineGate();
        $this->defineObserver();

        $charts->register([
            Charts\UserSpendingChart::class,
            Charts\Admin\StatisticUserChart::class,
            Charts\Admin\StatisticOrderChart::class,
            Charts\Admin\StatisticOrderRevenueChart::class,
            Charts\Admin\StatisticServiceImportProductPerHour::class,
        ]);
    }

    protected function defineGate()
    {
        Gate::after(function ($user, $ability, $result, $arguments) {
            if ($user->isAdministrator()) {
                return true;
            }
        });

        Gate::define('storage', fn (User $user) => $user->has_storage
            ? Response::allow()
            : Response::denyWithStatus(404));

        Gate::define('storage.container', fn (User $user, Storage $storage) => $user->has_storage && $storage->user_id === $user->id
            ? Response::allow()
            : Response::denyWithStatus(404));
    }

    protected function defineObserver()
    {
        User::observe(UserObserver::class);
        Product::observe(ProductObserver::class);
        Service::observe(ServiceObserver::class);
        Order::observe(OrderObserver::class);
    }
}
