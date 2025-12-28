<?php

namespace App\Providers;

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
            // Dummy notifikasi
            $notifications = [
                (object)[
                    'id' => 1,
                    'nama_obat' => 'Paracetamol',
                    'batch' => 'B001',
                    'exp_date' => now()->addDays(5),
                    'sisa_hari' => 5,
                    'stok' => 5,
                ],
                (object)[
                    'id' => 1,
                    'nama_obat' => 'Paracetamol',
                    'batch' => 'B002',
                    'exp_date' => now()->addDays(5),
                    'sisa_hari' => 5,
                    'stok' => 5,
                ],
                (object)[
                    'id' => 1,
                    'nama_obat' => 'Paracetamol',
                    'batch' => 'B003',
                    'exp_date' => now()->addDays(5),
                    'sisa_hari' => 5,
                    'stok' => 5,
                ],
                (object)[
                    'id' => 1,
                    'nama_obat' => 'Paracetamol',
                    'batch' => 'B004',
                    'exp_date' => now()->addDays(5),
                    'sisa_hari' => 5,
                    'stok' => 5,
                ],
            ];

            $view->with('notifications', $notifications);
        });
    }
}
