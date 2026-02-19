<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User; // Tambahkan ini
use App\Mail\ReportMasukMail; // Tambahkan ini
use Illuminate\Support\Facades\Mail; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Menampilkan daftar laporan dengan filter privasi
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        // Logic Filter Privasi & Eager Loading
        if ($user->role == 'supervisor' || $user->role == 'mekanik') {
            $comments = Comment::with(['alat', 'user', 'maintenances'])->latest()->get();
        } else {
            $comments = Comment::with(['alat', 'user', 'maintenances'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar History Laporan',
            'data' => $comments
        ], 200);
    }

    /**
     * Menyimpan laporan baru dari sisi User
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'content' => 'nullable|string',
            'voice_note' => 'nullable|file|mimes:mp3,wav,m4a,aac,mp4',
            'photo' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->alat_id = $request->alat_id;
        $comment->content = $request->input('content');
        $comment->status = 'pending';
        $comment->unique_code = 'REP-' . strtoupper(Str::random(8));

        if ($request->hasFile('voice_note')) {
            $comment->voice_note = $request->file('voice_note')->store('uploads/voices', 'public');
        }

        if ($request->hasFile('photo')) {
            $comment->photo = $request->file('photo')->store('uploads/photos', 'public');
        }

        if ($request->hasFile('video')) {
            $comment->video = $request->file('video')->store('uploads/videos', 'public');
        }

        $comment->save();

        // --- FITUR BARU: NOTIFIKASI EMAIL KE MEKANIK ---
        try {
            // Ambil semua email user yang punya role mekanik
            $emailsMekanik = User::where('role', 'mekanik')->pluck('email');

            if ($emailsMekanik->isNotEmpty()) {
                // Pastikan lu udah buat file ReportMasukMail pake php artisan ya!
                Mail::to($emailsMekanik)->send(new ReportMasukMail($comment->load('alat', 'user')));
            }
        } catch (\Exception $e) {
            // Kita pake try-catch biar kalau email gagal dikirim (misal sinyal server bapuk), 
            // laporannya tetep kesimpen di database.
            \Log::error("Gagal kirim email: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan shift berhasil tersimpan dan notifikasi terkirim!',
            'data' => $comment->load(['alat', 'user', 'maintenances']) 
        ], 201);
    }
}