<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPermintaanPembelian extends Mailable
{
    use Queueable, SerializesModels;

    public $permintaan;
    public $penilai;

    public function __construct($permintaan, $penilai)
    {
        $this->permintaan = $permintaan;
        $this->penilai = $penilai;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Permintaan Pembelian'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-permintaan-pembelian',
            with: [
                'permintaan' => $this->permintaan,
                'penilai' => $this->penilai,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
