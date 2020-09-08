<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InstagramRequest extends Mailable
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
	public $timeline;
    public function __construct($data,$timeline)
    {
        $this->data = $data;
        $this->timeline = $timeline;
        $this->marketmessage = 'mails.instagramrequest';
        $this->marketsubject = 'Informacion de Cliente.';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('gerencia.marketing@viajesplusultra.com','Dpto. Administracion, Plus Ultra C.A')
                    ->bcc('plusultradesarrollo@gmail.com')
                    ->subject($this->marketsubject)
                    ->view($this->marketmessage);
    }
}
