<?php namespace Twedoo\VolcatorGuard;

/**
 * This file is part of VolcatorGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\volcatorGuard
 */

use Illuminate\Support\ServiceProvider;

class VolcatorGuardServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/config.php' => app()->basePath() . '/config/volcator.php',
        ]);

        // Register commands
        $this->commands('command.volcator.migration.guard');

        // Register blade directives
        $this->bladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerVolcatorGuard();

        $this->registerCommands();

        $this->mergeConfig();
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        if (!class_exists('\Blade')) return;

        // Call to VolcatorGuard::hasRole
        \Blade::directive('role', function($expression) {
            return "<?php if (\\VolcatorGuard::hasRole({$expression})) : ?>";
        });

        \Blade::directive('endrole', function($expression) {
            return "<?php endif; // VolcatorGuard::hasRole ?>";
        });

        // Call to VolcatorGuard::can
        \Blade::directive('permission', function($expression) {
            return "<?php if (\\VolcatorGuard::can({$expression})) : ?>";
        });

        \Blade::directive('endpermission', function($expression) {
            return "<?php endif; // VolcatorGuard::can ?>";
        });

        // Call to VolcatorGuard::ability
        \Blade::directive('ability', function($expression) {
            return "<?php if (\\VolcatorGuard::ability({$expression})) : ?>";
        });

        \Blade::directive('endability', function($expression) {
            return "<?php endif; // VolcatorGuard::ability ?>";
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerVolcatorGuard()
    {
        $this->app->bind('volcatorGuard', function ($app) {
            return new VolcatorGuard($app);
        });

        $this->app->bind('volcatorGuardByApplication', function ($app) {
            return new VolcatorGuardByApplication($app);
        });

        $this->app->alias('volcatorGuard', 'Twedoo\VolcatorGuard\VolcatorGuard');
        $this->app->alias('volcatorGuardByApplication', 'Twedoo\VolcatorGuard\VolcatorGuardByApplication');
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.volcator.migration.guard', function ($app) {
            return new MigrationCommand();
        });
    }

    /**
     * Merges user's and volcatorGuard's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'volcator'
        );
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.volcator.migration.guard'
        ];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/volcator.php' => config_path('volcator.php'),
        ], 'volcator.config');
    }
}
