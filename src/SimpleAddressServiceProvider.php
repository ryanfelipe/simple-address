<?php

namespace Fndmiranda\SimpleAddress;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Fndmiranda\SimpleAddress\Console;

class SimpleAddressServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if (config('address.manager_address')) {
                $this->registerMigrations();

                $this->publishes([
                    __DIR__ . '/../database/migrations' => database_path('migrations'),
                ], 'simple-address-migrations');
            }

            $this->publishes([
                __DIR__ . '/../config/address.php' => config_path('address.php'),
            ], 'simple-address-config');

            $this->commands([
                Console\SimpleAddressMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/address.php', 'address');

        App::bind('address', Address::class);
    }

    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Address::$runsMigrations) {
            return $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
