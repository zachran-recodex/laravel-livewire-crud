<?php

namespace App\Providers;

use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(\App\Models\User::class),
                $app->make(\Spatie\Permission\Models\Role::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
