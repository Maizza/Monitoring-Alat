<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\UserController;
use App\Models\Alat;
use App\Models\Comment; // Tambahkan ini jika butuh hitung data
use App\Models\Maintenance; // Tambahkan ini jika butuh hitung data

// --- REDIRECT UTAMA KE LOGIN ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest (Hanya yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

// Auth (Hanya yang sudah login)
Route::middleware('auth')->group(function () {
    
    // Dashboard dengan Summary Data
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalAlat' => Alat::count(),
            // Hitung total repair dari tabel comments yang statusnya bukan DONE
            'totalRepair' => Comment::whereIn('status', ['pending', 'Repairing', 'PROSES'])->count(),
            // Hitung total maintenance dari tabel maintenances
            'totalMaintenance' => Maintenance::count(), 
        ]);
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CRUD Alat (Pastikan Controller lu sudah pakai indexWeb agar $alats tidak undefined)
    Route::get('/alat/create', [AlatController::class, 'indexWeb'])->name('alat.index');
    Route::post('/alat/store', [AlatController::class, 'store'])->name('alat.store');
    Route::get('/alat/{alat}/edit', [AlatController::class, 'edit'])->name('alat.edit');
    Route::put('/alat/{alat}', [AlatController::class, 'update'])->name('alat.update');
    Route::delete('/alat/{alat}', [AlatController::class, 'destroy'])->name('alat.destroy');

    // CRUD Users
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});