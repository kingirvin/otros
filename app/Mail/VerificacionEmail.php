<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\User_verificacion;

class VerificacionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verficiacion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, User_verificacion $verficiacion)
    {
        $this->user = $user;
        $this->verficiacion = $verficiacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verificación de correo')->view('email.verificacion');
    }
}
