<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookErrorMail extends Mailable
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
        $this->marketmessage = 'mails.error_booking';

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ventas45@ultra-api.com', 'Dpto. De ventas Plus Ultra, C.A.')
                    ->subject('Ha ocurrido un error reservando.')
                    ->attach('storage/logs/cachekiu/ERROR_BOOKING')
                    ->view($this->marketmessage);
    }
}
