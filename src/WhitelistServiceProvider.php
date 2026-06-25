<?php
namespace VEximweb\Core\Whitelist;

use Filament\Panel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class WhitelistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/whitelist.php',
            'whitelist'
        );

        Panel::configureUsing(function (Panel $panel) {
            $panel->plugin(WhitelistPlugin::make());
        });
    }

    public function boot(): void
    {

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //$this->loadViewsFrom(__DIR__ . '/../resources/views', 'whitelist');

        $this->publishes([
            __DIR__ . '/../config/whitelist.php' => config_path('whitelist.php'),
        ], 'whitelist-config');

    }
}
