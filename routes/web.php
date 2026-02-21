<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaintenanceController; 
use App\Models\Alat;
use App\Models\Comment;
use App\Models\Maintenance;

// --- REDIRECT UTAMA ---
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

Route::middleware('auth')->group(function () {
    
    // Dashboard dengan Ringkasan Data
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalAlat' => Alat::count(),
            'totalRepair' => Comment::whereIn('status', ['pending', 'Repairing', 'PROSES'])->count(),
            'totalMaintenance' => Maintenance::where('status_kerja', 'DONE')->count(), 
        ]);
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /* | --- OPERATIONS (REPAIR & MAINTENANCE) --- */
    
    // Menu Repair (Laporan Operator)
    Route::get('/repair', [MaintenanceController::class, 'repairIndex'])->name('repair.index');
    Route::get('/repair/export', [MaintenanceController::class, 'repairExport'])->name('repair.export');
    Route::delete('/repair/{id}', [MaintenanceController::class, 'repairDestroy'])->name('repair.destroy'); // Rute Hapus Repair

    // Menu Data Maintenance (Histori Mekanik)
    Route::get('/report', [MaintenanceController::class, 'maintenanceIndex'])->name('maintenance.index');
    Route::get('/report/export', [MaintenanceController::class, 'reportExport'])->name('report.export');
    Route::delete('/maintenance/{id}', [MaintenanceController::class, 'maintenanceDestroy'])->name('maintenance.destroy'); // Rute Hapus Maintenance

    /* | --- CRUD ALAT --- */
    Route::get('/alat', [AlatController::class, 'index'])->name('alat.index');
    Route::post('/alat', [AlatController::class, 'store'])->name('alat.store');
    Route::get('/alat/{alat}/edit', [AlatController::class, 'edit'])->name('alat.edit');
    Route::put('/alat/{alat}', [AlatController::class, 'update'])->name('alat.update');
    Route::delete('/alat/{alat}', [AlatController::class, 'destroy'])->name('alat.destroy');

    /* | --- CRUD USERS --- */
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});