<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $approver;

    public function __construct($data, $approver)
    {
        $this->data = $data;
        $this->approver = $approver;
    }

    public function build()
    {
        return $this->subject('Permintaan Persetujuan')
            ->view('emails.approval-berikutnya');
    }
}
