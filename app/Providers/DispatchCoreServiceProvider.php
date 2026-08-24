<?php

namespace App\Providers;

use App\Services\DispatchCoreApi;
use Illuminate\Support\ServiceProvider;

class DispatchCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DispatchCoreApi::class, function () {
            return new DispatchCoreApi(
                (string) config('dispatch_core.base_url'),
                (string) config('dispatch_core.admin_key')
            );
        });
    }
}
