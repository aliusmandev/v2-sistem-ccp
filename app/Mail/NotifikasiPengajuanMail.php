<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPengajuanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;
    public $hta;

    public function __construct($pengajuan, $hta)
    {
        $this->pengajuan = $pengajuan;
        $this->hta = $hta;
    }

    public function build()
    {
        return $this
            ->subject('Notifikasi Pengajuan HTA')
            ->view('Notifikasi-Email.Hta-Gpa');
    }
}
