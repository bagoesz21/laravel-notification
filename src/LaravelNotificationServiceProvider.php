<?php

namespace Bagoesz21\LaravelNotification;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Bagoesz21\LaravelNotification\Config\NotifConfig;

class LaravelNotificationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-notification');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'bagoesz21');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/notification.php', 'notification');

        // Register the service the package provides.
        $this->app->singleton('laravel-notification', function ($app) {
            return new LaravelNotification;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-notification'];
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
            __DIR__.'/../config/notification.php' => config_path('notification.php'),
        ], 'laravel-notification.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bagoesz21'),
        ], 'laravel-notification.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/bagoesz21'),
        ], 'laravel-notification.views');*/

        // Publishing the translation files.
        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath('vendor/laravel-notification'),
        ], 'laravel-notification.lang');

        $this->publishMigrations();

        // Registering package commands.
        // $this->commands([]);
    }

    /**
     * @return void
     */
    protected function publishMigrations()
    {
        $migrations = [
            __DIR__.'/../database/migrations/create_notifications_table.php.stub' =>
            $this->getMigrationFileName('create_notifications_table.php')
        ];

        $notifConfig = NotifConfig::make();
        if ($notifConfig->get('tables.notification_log.enabled', false)){
            $migrations = array_merge($migrations, [
                __DIR__.'/../database/migrations/create_notification_logs_table.php.stub' =>
                $this->getMigrationFileName('create_notification_logs_table.php'),
            ]);
        }

        if ($notifConfig->get('tables.notification_template.enabled', false)){
            $migrations = array_merge($migrations, [
                __DIR__.'/../database/migrations/create_notification_templates_table.php.stub' =>
                $this->getMigrationFileName('create_notification_templates_table.php'),
            ]);
        }

        $this->publishes($migrations, 'migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
