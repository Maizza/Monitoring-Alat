<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RepairSelesaiMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Definisikan variabel public agar bisa dibaca di view/blade
    public $maintenance;
    public $comment;

    /**
     * Create a new message instance.
     * Kita tangkap data dari MaintenanceController di sini
     */
    public function __construct($maintenance, $comment)
    {
        $this->maintenance = $maintenance;
        $this->comment = $comment;
    }

    /**
     * Get the message envelope.
     * Mengatur subjek email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Perbaikan Alat: ' . ($this->comment->alat->nama_alat ?? 'Alat Anda'),
        );
    }

    /**
     * Get the message content definition.
     * Mengarahkan ke file HTML (blade) yang sudah kita buat tadi
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.repair_selesai', // Pastikan filenya ada di resources/views/emails/repair_selesai.blade.php
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}