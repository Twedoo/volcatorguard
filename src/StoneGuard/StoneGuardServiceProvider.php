<?php namespace Twedoo\StoneGuard;

/**
 * This file is part of StoneGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\stoneGuard
 */

use Illuminate\Support\ServiceProvider;

class StoneGuardServiceProvider extends ServiceProvider
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
            __DIR__.'/../config/config.php' => app()->basePath() . '/config/stoneGuard.php',
        ]);

        // Register commands
        $this->commands('command.stoneGuard.migration');

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
        $this->registerStoneGuard();

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

        // Call to StoneGuard::hasRole
        \Blade::directive('role', function($expression) {
            return "<?php if (\\StoneGuard::hasRole({$expression})) : ?>";
        });

        \Blade::directive('endrole', function($expression) {
            return "<?php endif; // StoneGuard::hasRole ?>";
        });

        // Call to StoneGuard::can
        \Blade::directive('permission', function($expression) {
            return "<?php if (\\StoneGuard::can({$expression})) : ?>";
        });

        \Blade::directive('endpermission', function($expression) {
            return "<?php endif; // StoneGuard::can ?>";
        });

        // Call to StoneGuard::ability
        \Blade::directive('ability', function($expression) {
            return "<?php if (\\StoneGuard::ability({$expression})) : ?>";
        });

        \Blade::directive('endability', function($expression) {
            return "<?php endif; // StoneGuard::ability ?>";
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerStoneGuard()
    {
        $this->app->bind('stoneGuard', function ($app) {
            return new StoneGuard($app);
        });

        $this->app->alias('stoneGuard', 'Twedoo\StoneGuard\StoneGuard');
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.stoneGuard.migration', function ($app) {
            return new MigrationCommand();
        });
    }

    /**
     * Merges user's and stoneGuard's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'stoneGuard'
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
            'command.stoneGuard.migration'
        ];
    }
}
