<?php

namespace VEximweb\Core\Whitelist;

use Filament\Contracts\Plugin;
use Filament\Panel;
use VEximweb\Core\Whitelist\Filament\Resources\WhitelistResource;

class WhitelistPlugin implements Plugin
{
    
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());
        return $plugin;
    }    
    
    public function getId(): string
    {
        return 'whitelist';
    }

    public function register(Panel $panel): void
    {
        // Register the Group resource
        $panel->resources([
            WhitelistResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // Any boot logic
    }
}
