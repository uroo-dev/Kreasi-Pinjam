<?php

namespace App\Providers;

use App\Support\Notifikasi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('Admin.partials.topbar', function ($view) {
            $view->with('notifikasi', Notifikasi::untuk());
        });
    }
}
