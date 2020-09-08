<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditCardPayments extends Mailable
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
        switch ($this->data->st) {
          case 1:
            $this->marketmessage = 'gateways.creditcard.mails.creditcardpaid';
            $this->marketsubject = 'Su pago ha sido procesado.';
            break;
          case 3:
            $this->marketmessage = 'gateways.creditcard.mails.creditcardrefuse';
            $this->marketsubject = 'Su pago ha sido rechazado.';
            break;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ventas45@ultra-api.com','Dpto. Administracion, Plus Ultra C.A')
                    ->subject($this->marketsubject)
                    ->view($this->marketmessage);
    }
}