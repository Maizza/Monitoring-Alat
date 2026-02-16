<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        // Mengambil semua data alat untuk pilihan di Flutter
        $alats = Alat::all();
        return response()->json([
            'status' => 'success',
            'data' => $alats
        ]);
    }
}