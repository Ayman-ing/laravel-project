<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
{
    $this->app->bind(PatientService::class, function ($app) {
        return new PatientService();
    });
    $this->app->bind(
        \App\PatientRepositoryInterface::class,
        \App\Repositories\PatientRepository::class
    );
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
