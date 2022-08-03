<?php

namespace App\Providers;

use App\Models\Storage;
use App\Models\StorageContainer;
use App\Policies\StorageContainerPolicy;
use App\Policies\StoragePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Storage::class => StoragePolicy::class,
        StorageContainer::class => StorageContainerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
