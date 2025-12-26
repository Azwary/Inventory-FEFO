<?php

use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\Notifikasi;
use App\Http\Controllers\pengeluaran;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Stok;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
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
    Route::get('/', [AdminDashboard::class, 'index'])->name('index');
    Route::get('/dashboard', [AdminDashboard::class, 'dashboard'])->name('dashboard');
    Route::get('/stok', [Stok::class, 'index'])->name('stok.index');
    Route::get('/stok/{id}', [Stok::class, 'show'])->name('obat.show');
    Route::get('/pengeluaran', [pengeluaran::class, 'index'])->name('pengeluaran-obat.index');
    Route::get('/notifikasi', [Notifikasi::class, 'index'])->name('notifikasi-kedaluwarsa.index');

    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');
});


Route::middleware(['auth', 'role:Pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pimpinan.dashboard');
    })->name('dashboard');
});
Route::get('/test', function () {
    $user = Auth::user();
    dd($user);
});
require __DIR__ . '/auth.php';
