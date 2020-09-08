<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CanceledLocalizableAlertMail extends Mailable
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
        $this->marketmessage = 'mails.check_itin';

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ventas45@ultra-api.com', 'Dpto. De ventas Plus Ultra, C.A.')
                    ->bcc('plusultradesarrollo@gmail.com')
                    ->subject('Un itinerario ha sido cancelado por el sistema de KIU.')
                    ->view($this->marketmessage);
    }
}
