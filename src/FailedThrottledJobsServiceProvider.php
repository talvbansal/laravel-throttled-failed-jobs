<?php


namespace TalvBansal\ThrottledFailedJobMonitor;


use Illuminate\Support\ServiceProvider;

class FailedThrottledJobsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/throttled-failed-jobs.php' => config_path('throttled-failed-jobs.php'),
        ], 'config');
        $this->app->make(FailedJobNotifier::class)->register();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/throttled-failed-jobs.php',
            'throttled-failed-jobs'
        );
        $this->app->singleton(FailedJobNotifier::class);
    }
}
