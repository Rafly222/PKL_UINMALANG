<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

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
        View::composer('layouts.app', function ($view) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                $sidebarPendingCount = User::where('status', 'pending')->count();
                $view->with('sidebarPendingCount', $sidebarPendingCount);
            } else {
                $view->with('sidebarPendingCount', 0);
            }
        });
    }
}

