<?php

namespace App\Providers;

use App\Models\StokBarang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

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
            $notifications = StokBarang::select(
                '*',
                \DB::raw('DATEDIFF(tanggal_kadaluarsa, CURDATE()) AS sisa_hari')
            )
                ->whereNotNull('tanggal_kadaluarsa')
                ->whereBetween('tanggal_kadaluarsa', [now(), now()->addDays(30)])
                ->get();

            $view->with('notifications', $notifications);
        });
    }
}
