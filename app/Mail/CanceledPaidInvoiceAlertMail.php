<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CanceledPaidInvoiceAlertMail extends Mailable
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
        $this->marketmessage = 'mails.canceled_alert';

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
                    ->subject('Ha sido cancelada una factura pagada.')
                    ->view($this->marketmessage);
    }
}
