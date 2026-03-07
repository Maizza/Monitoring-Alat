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

class CommentController extends Controller
{
    /**
     * Menampilkan daftar laporan dengan filter privasi
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        // FIX: Tambahkan 'maintenances.user' agar nama mekanik ikut terkirim
        $relations = ['alat', 'user', 'maintenances.user'];

        if ($user->role == 'supervisor' || $user->role == 'mekanik') {
            $comments = Comment::with($relations)->latest()->get();
        } else {
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

        // NOTIFIKASI EMAIL
        try {
            $emailsMekanik = User::where('role', 'mekanik')->pluck('email');
            if ($emailsMekanik->isNotEmpty()) {
                // Pastikan Mail class ReportMasukMail menggunakan ShouldQueue agar tidak delay
                Mail::to($emailsMekanik)->send(new ReportMasukMail($comment->load('alat', 'user')));
            }
        } catch (\Exception $e) {
            \Log::error("Gagal kirim email: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan shift berhasil tersimpan!',
            // FIX: Tambahkan 'maintenances.user' juga di response return
            'data' => $comment->load(['alat', 'user', 'maintenances.user']) 
        ], 201);
    }
}