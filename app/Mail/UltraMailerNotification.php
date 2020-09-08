<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UltraMailerNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recipient)
    {
        if($recipient != ''){
            //dd($recipient->EMAIL2);
            $this->second = $recipient->EMAIL2;
        }
        
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(property_exists($this, 'second')){
            return  $this->from('gerencia.marketing@viajesplusultra.com','Dpto. Marketing, Plus Ultra C.A')
                    ->bcc($this->second)
                    ->subject('Maracaibo - Miami 2x1')
                    ->attach(public_path('images/post_36.jpg'))
                    //->attach(public_path('images/logo_pultrap.png'))
                    ->view('mails.ultramailer');
        }
        return  $this->from('gerencia.marketing@viajesplusultra.com','Dpto. Marketing, Plus Ultra C.A')
                    ->subject('Maracaibo - Miami 2x1')
                    ->attach(public_path('images/post_36.jpg'))
                    //->attach(public_path('images/logo_pultrap.png'))
                    ->view('mails.ultramailer');
    }
}