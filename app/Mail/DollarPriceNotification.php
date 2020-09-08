<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DollarPriceNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $data;
     public $marketmessage;
     public $marketsubject;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ventas45@ultra-api.com','Dpto. Administracion, Plus Ultra C.A')
                    ->bcc($this->data->emails)
                    ->subject('Actualización del Precio del Dólar')
                    ->view('mails.dollarpriceupdate');
    }
}