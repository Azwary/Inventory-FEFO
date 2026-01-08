<?php

namespace App\Providers;

use App\Models\StokBarang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        View::composer('*', function ($view) {

            $notifications = StokBarang::select(
                '*',
                DB::raw('DATEDIFF(tanggal_kadaluarsa, CURDATE()) AS sisa_hari')
            )
                ->where('jumlah_masuk', '>', 0)
                ->whereNotNull('tanggal_kadaluarsa')
                ->whereBetween('tanggal_kadaluarsa', [
                    now()->toDateString(),
                    now()->addDays(30)->toDateString()
                ])
                ->orderBy('tanggal_kadaluarsa', 'asc')
                ->get();

            $view->with('notifications', $notifications);
        });
    }
}
