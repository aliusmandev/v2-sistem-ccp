<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiDisposisiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $disposisi;
    public $approval;
    public $MasterVendor;
    public $MasterBarang;


    public function __construct($disposisi, $approval, $MasterVendor, $MasterBarang)
    {
        $this->disposisi = $disposisi;
        $this->approval = $approval;
        $this->MasterVendor = $MasterVendor;
        $this->MasterVendor = $MasterBarang;
    }

    public function build()
    {
        return $this->subject('Permohonan Persetujuan Lembar Disposisi')
            ->view('emails.notifikasi-disposisi');
    }
}
