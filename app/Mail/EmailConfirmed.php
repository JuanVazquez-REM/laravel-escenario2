<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public $verificacion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verificacion)
    {
        $this->verificacion = $verificacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.email');
    }
}
