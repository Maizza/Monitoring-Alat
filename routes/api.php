<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AlatController;
use App\Http\Controllers\Api\MaintenanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route Public (Bisa diakses tanpa login)
Route::post('/login', [AuthController::class, 'login']);

// Route Protected (Wajib bawa Token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Ambil info user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout untuk hapus token
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- FITUR MONITORING ALAT BERAT ---

    // User (Operator): Kirim laporan shift (Teks/Voice/Foto/Video) [cite: 5, 9, 10]
    Route::post('/comments', [CommentController::class, 'store']);

    // Mekanik: Baca laporan/comment dari User 
    Route::get('/comments', [CommentController::class, 'index']);
    
    // Tambahin route buat Mekanik bikin Repair/Preventive nanti di sini
    // Route::post('/maintenance', [MaintenanceController::class, 'store']);
    Route::get('/alats', [AlatController::class, 'index']);
    Route::get('/history-laporan', [CommentController::class, 'index']);

    Route::post('/maintenances', [MaintenanceController::class, 'store']);
});
