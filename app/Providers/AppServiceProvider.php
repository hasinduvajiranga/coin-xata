<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\IAccountRepository::class,
            \App\Repositories\Eloquent\EloquentAccountRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ITransactionRepository::class,
            \App\Repositories\Eloquent\EloquentTransactionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\IBudgetRepository::class,
            \App\Repositories\Eloquent\EloquentBudgetRepository::class
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
