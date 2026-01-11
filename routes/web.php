<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Pimpinan\DashboardController as PimpinanDashboardController;
use App\Http\Controllers\Pimpinan\LaporanController as PimpinanLaporanController;
use App\Http\Controllers\Pimpinan\NotifikasiController as PimpinanNotifikasiController;
use App\Http\Controllers\Pimpinan\PengeluaranController as PimpinanPengeluaranController;
use App\Http\Controllers\Pimpinan\StokController as PimpinanStokController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()

        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'Pimpinan') {
        return redirect()->route('pimpinan.dashboard');
    }

    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
    Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
    Route::get('/stok/{id}', [StokController::class, 'show'])->name('obat.show');
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran-obat.index');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran-obat.store');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi-kedaluwarsa.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan-audit.index');
    Route::get('/laporan/export/csv', [LaporanController::class, 'exportCsv'])->name('laporan-audit.export.csv');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan-audit.export.pdf');
});


Route::middleware(['auth', 'role:Pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/', [PimpinanDashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [PimpinanDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/stok', [PimpinanStokController::class, 'index'])->name('stok.index');
    Route::get('/stok/{id}', [PimpinanStokController::class, 'show'])->name('obat.show');
    Route::get('/notifikasi', [PimpinanNotifikasiController::class, 'index'])->name('notifikasi-kedaluwarsa.index');
    Route::get('/laporan', [PimpinanLaporanController::class, 'index'])->name('laporan-audit.index');
    Route::get('/laporan/export/csv', [PimpinanLaporanController::class, 'exportCsv'])->name('laporan-audit.export.csv');
    Route::get('/laporan/export/pdf', [PimpinanLaporanController::class, 'exportPdf'])->name('laporan-audit.export.pdf');
});

Route::get('/test', function () {
    $user = Auth::user();
    dd($user);
});
require __DIR__ . '/auth.php';
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});
