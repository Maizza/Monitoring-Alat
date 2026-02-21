<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Tambahkan ini agar kirim cepat
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportMasukMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Properti public agar bisa langsung diakses di view emails.report_masuk
    public $comment; 

    /**
     * Create a new message instance.
     */
    public function __construct($comment)
    {
        // Model Comment otomatis di-serialize untuk antrean database
        $this->comment = $comment;
    }

    /**
     * Get the message envelope.
     * Mengatur subjek email notifikasi laporan baru
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Laporan Kerusakan Baru: ' . ($this->comment->alat->nama_alat ?? 'Perangkat'),
        );
    }

    /**
     * Get the message content definition.
     * Mengarahkan ke template HTML laporan masuk
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report_masuk',
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