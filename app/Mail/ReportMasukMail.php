<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMasukMail extends Mailable
{
    use Queueable, SerializesModels;

    // WAJIB public biar kebaca di Blade (HTML)
    public $comment; 

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->subject('Ada Laporan Kerusakan Baru!')
                    ->view('emails.report_masuk');
    }
}