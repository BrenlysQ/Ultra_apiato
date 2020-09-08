<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BankTransferEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $payment;
     public $marketmessage;
     public $marketsubject;
    public function __construct($payment)
    {
        $this->payment = $payment;
        //Si el status del pago (trasnferemcia es 1 = Pago registrado, 2 = transferencia confirmada ,3 = Trans rechzada)
        switch ($this->payment->st) {
          case 1:
            $this->marketmessage = 'gateways.banktransfer.mails.payment_sent';
            $this->marketsubject = 'Hemos registrado su pago.';
            break;
          case 2:
            $this->marketmessage = 'gateways.banktransfer.mails.transfer_confirm';
            $this->marketsubject = 'Su pago ha sido verificado.';
            break;
          case 3:
            $this->marketmessage = 'gateways.banktransfer.mails.transfer_bounce';
            $this->marketsubject = 'No hemos podido verificar su pago.';
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
        return $this->from('api@plusultra-desarrollo.com.ve','Dpto. Administracion, Plus Ultra C.A')
                    ->bcc('plusultradesarrollo@gmail.com')
                    ->subject($this->marketsubject)
                    ->view($this->marketmessage);
    }
}
