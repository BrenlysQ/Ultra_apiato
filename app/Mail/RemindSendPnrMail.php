<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemindSendPnrMail extends Mailable
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
        $this->marketmessage = 'mails.pnr_reminder';

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ventas45@ultra-api.com', 'Depto. De ventas Plus Ultra, C.A.')
                    ->bcc('plusultradesarrollo@gmail.com')
                    ->subject('Estamos esperando por sus datos para completar su reserva.')
                    ->view($this->marketmessage);
    }
}
