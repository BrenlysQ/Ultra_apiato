<?php

namespace App\Containers\UltraMailer\Tasks;
use Mail;
use App\Ship\Parents\Tasks\Task;
use App\Mail\UltraMailerNotification;
use App\Containers\UltraMailer\Models\UltraMailer;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class SendMailsTask extends Task
{
    public function run() {
        $recipients = UltraMailer::all();
		// dd($recipients);
		foreach ($recipients as $index => $recipient) {
			//dd($recipient);
			// dd($recipient->email1, $recipient->email2);
			if($recipient->email1 != null and $recipient->email2 != null){
				//dd($recipient);
				Mail::to($recipient->email1)->send(new UltraMailerNotification($recipient));
			}else{
				if($recipient->email1){
					Mail::to($recipient->email1)->send(new UltraMailerNotification(''));
				}else{
					Mail::to($recipient->email2)->send(new UltraMailerNotification(''));
				}
			}
			if(($index + 1) % 10 != 0 ){
				sleep(1);
			}else{
				sleep(300);
			}
			echo 'Correo enviado a: ' . $recipient->name . '\n';
		}
    }
}