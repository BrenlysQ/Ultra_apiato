<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItineraryIssue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
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
        return $this->from('ventas45@ultra-api.com','Dpto. Ventas Plus Ultra C.A')
                    ->bcc('plusultradesarrollo@gmail.com')
                    ->subject('Se ha emitido un pago.')
                    ->view('mails.itineraryissue');
    }
}
