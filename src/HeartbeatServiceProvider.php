<?php

namespace MortenScheel\Heartbeat;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use MortenScheel\Heartbeat\Commands\MonitorHeartbeatCommand;

class HeartbeatServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/heartbeat.php' => config_path('heartbeat.php'),
            ], 'heartbeat-config');
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/heartbeat'),
            ], 'heartbeat-views');
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'heartbeat');
            $this->commands([
                MonitorHeartbeatCommand::class
            ]);
            if (config('heartbeat.enabled')) {
                $this->app->booted(function () {
                    $this->app->make(Schedule::class)
                        ->command('heartbeat:monitor')
                        ->everyMinute();
                });
            }
        }
    }

    /**
     * Register any package services.
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/heartbeat.php', 'heartbeat');
        // Register the service the package provides.
        $this->app->singleton('heartbeat', function ($app) {
            return new HeartbeatMonitor;
        });
    }
}
