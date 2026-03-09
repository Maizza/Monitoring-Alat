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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'content' => 'nullable|string',
            'voice_note' => 'nullable|file|mimes:mp3,wav,m4a,aac,mp4,m4r',
            'photo' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        // --- PROTEKSI ANTI DUPLIKAT (SATPAM SERVER) ---
        // Cek apakah user yang sama mengirim laporan untuk alat yang sama dalam 10 detik terakhir
        $existing = Comment::where('user_id', auth()->id())
            ->where('alat_id', $request->alat_id)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Sabar King, laporan sebelumnya sedang diproses server!'
            ], 422); // Error 422: Unprocessable Content
        }

        return DB::transaction(function () use ($request) {
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

            try {
                $emailsMekanik = User::where('role', 'mekanik')->pluck('email');
                if ($emailsMekanik->isNotEmpty()) {
                    Mail::to($emailsMekanik)->send(new ReportMasukMail($comment->load('alat', 'user')));
                }
            } catch (\Exception $e) {
                Log::error("Email Error: " . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Laporan shift berhasil tersimpan!',
                'data' => $comment->load(['alat', 'user', 'maintenances.user']) 
            ], 201);
        });
    }
}