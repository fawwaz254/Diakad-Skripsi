<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $data_email;

    public function __construct($data_email)
    {
        $this->data_email = $data_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[Diakad ' . $this->data_email['sekolah'] . '] Link Reset Password')
            ->view('template-email.forget-password', [
                'nm_sekolah' => $this->data_email['sekolah'],
                'nama' => $this->data_email['nama'],
                'url' => $this->data_email['url'],
                'link_sekolah' => $this->data_email['url_sekolah'],
            ]);
    }
}
