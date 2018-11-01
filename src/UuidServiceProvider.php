<?php

namespace Waska\LaravelUuid;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UuidServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAliases();
        $this->registerConfig();
    }

    /**
     * Register aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Waska\\Uuid', 'Waska\\LaravelUuid\\Generator\\Uuid');
        $loader->alias('Waska\\Traits\\Uuid', 'Waska\\LaravelUuid\\Traits\\UuidTrait');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/waska.uuid.php', 'waska.uuid'
        );
    }

    /**
     * Publish config. [Command: php artisan vendor:publish --tag=waska-uuid-config]
     *
     * @return void
     */
    protected function publishConfig()
    {
        $this->publishes([__DIR__ . '/../config/waska.uuid.php' => config_path('waska.uuid.php')], 'waska-uuid-config');
    }
}
