<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $invoice;
     public $marketmessage;
     public $marketsubject;
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
        switch ($this->invoice->st) {
          case 1:
            $this->marketmessage = 'invoices.mails.invoicepaid';
            $this->marketsubject = 'Su orden ha sido confirmada.';
            break;
          case 3:
            $this->marketmessage = 'includes.emails.payment_confirm';
            $this->marketsubject = 'Su pago ha sido verificado.';
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
