<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\UserController;
use App\Models\Alat;

// Guest (Hanya yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

// Auth (Hanya yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/alat/create', [AlatController::class, 'index'])->name('alat.index');
    Route::post('/alat/store', [AlatController::class, 'store'])->name('alat.store');
    Route::delete('/alat/{alat}', [AlatController::class, 'destroy'])->name('alat.destroy');
    Route::get('/alat/{alat}/edit', [AlatController::class, 'edit'])->name('alat.edit');
    Route::put('/alat/{alat}', [AlatController::class, 'update'])->name('alat.update');

    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalAlat' => Alat::count(),
            'totalRepair' => Alat::where('status', 'Repair')->count(), // Contoh jika data repair diambil dari status di tabel alat
            'totalMaintenance' => 0, // Ganti dengan Model::count() jika sudah ada tabelnya
        ]);
    })->middleware('auth');


    Route::middleware(['auth'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::post('/users', [UserController::class, 'store'])->name('user.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit'); // Tambahkan ini
        Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update'); // Tambahkan ini
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });
});