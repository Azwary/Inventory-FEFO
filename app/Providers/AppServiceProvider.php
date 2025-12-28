<?php

namespace App\Providers;

use App\Models\StokBarang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


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
    public function boot()
    {
        View::composer('*', function ($view) {
            $notifications = StokBarang::select('*')
                ->whereNotNull('tanggal_kadaluarsa')
                ->where('tanggal_kadaluarsa', '<=', now()->addDays(30))
                ->where('tanggal_kadaluarsa', '>=', now())
                ->get();


            $view->with('notifications', $notifications);
        });
    }
}
