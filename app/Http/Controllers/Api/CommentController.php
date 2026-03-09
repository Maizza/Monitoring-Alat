<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User; 
use App\Mail\ReportMasukMail; 
use Illuminate\Support\Facades\Mail; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk kestabilan data

class CommentController extends Controller
{
    /**
     * Menampilkan daftar laporan dengan filter privasi
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        // Eager Loading sampai ke level user mekanik
        $relations = ['alat', 'user', 'maintenances.user'];

        // Mekanik & Supervisor HARUS liat semua laporan agar bisa kerja
        if ($user->role == 'supervisor' || $user->role == 'mekanik') {
            $comments = Comment::with($relations)->latest()->get();
        } else {
            // Operator biasa cuma liat laporan dia sendiri
            $comments = Comment::with($relations)
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
     * Menyimpan laporan baru dari sisi User (Operator)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'content' => 'nullable|string',
            'voice_note' => 'nullable|file|mimes:mp3,wav,m4a,aac,mp4,m4r',
            'photo' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        // Gunakan Transaction biar data aman & gak nanggung kalau ada error sinyal
        return DB::transaction(function () use ($request) {
            $comment = new Comment();
            $comment->user_id = auth()->id();
            $comment->alat_id = $request->alat_id;
            $comment->content = $request->input('content');
            $comment->status = 'pending'; // Status awal masuk tab Repair
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

            // NOTIFIKASI EMAIL KE MEKANIK
            // Pakai try-catch agar kalau email error, simpan data TETAP sukses
            try {
                $emailsMekanik = User::where('role', 'mekanik')->pluck('email');
                if ($emailsMekanik->isNotEmpty()) {
                    Mail::to($emailsMekanik)->send(new ReportMasukMail($comment->load('alat', 'user')));
                }
            } catch (\Exception $e) {
                \Log::error("Email Error: " . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Laporan shift berhasil tersimpan!',
                // Pastikan return data punya relasi lengkap agar UI Flutter langsung update
                'data' => $comment->load(['alat', 'user', 'maintenances.user']) 
            ], 201);
        });
    }
}