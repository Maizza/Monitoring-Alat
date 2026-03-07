<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AkunBaruMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        // Password dihapus, cuma bawa data user
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Akun Monitoring Alat Refinol: ' . $this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.akun_baru',
        );
    }
}