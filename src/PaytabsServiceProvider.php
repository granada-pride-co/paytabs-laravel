<?php

declare(strict_types=1);

namespace GranadaPride\Paytabs;

use Illuminate\Support\ServiceProvider;

class PaytabsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('paytabs', function ($app) {
            return new Paytabs;
        });

        $this->mergeConfigFrom(
            __DIR__.'/config/paytabs.php', 'paytabs'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/paytabs.php' => config_path('paytabs.php'),
        ]);
    }
}
