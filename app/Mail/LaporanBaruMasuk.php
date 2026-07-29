<?php
// app/Mail/LaporanBaruMasuk.php
namespace App\Mail;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LaporanBaruMasuk extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Laporan $laporan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Simadang] Laporan Baru Masuk - ' . $this->laporan->kode,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.laporan-baru',
        );
    }
}
