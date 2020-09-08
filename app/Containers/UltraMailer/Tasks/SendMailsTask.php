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
    public function run($recipients = false) {
    	
    	if (!$recipients) {
        	$recipients = UltraMailer::orderBy('id', 'desc')->get();
    	}
    	
		// dd($recipients);
		foreach ($recipients as $index => $recipient) {
			// dd($recipient);
			// dd($recipient->EMAIL1, $recipient->email2);
			if($recipient->email1 and $recipient->email2){
				if($this->isValidEmail(	$recipient->email1) and $this->isValidEmail($recipient->email2)){
					//dd($recipient);
					Mail::to($recipient->email1)->send(new UltraMailerNotification($recipient));
					echo 'Correo enviado a: ' . $recipient->name . '\n';
				}
			}else{
				if($recipient->email1){
					if($this->isValidEmail($recipient->email1)){
						Mail::to($recipient->email1)->send(new UltraMailerNotification(''));
						echo 'Correo enviado a: ' . $recipient->name . '\n';
					}
				}else{
					if($this->isValidEmail($recipient->email2)){
						Mail::to($recipient->email2)->send(new UltraMailerNotification(''));
						echo 'Correo enviado a: ' . $recipient->name . ' ';
					}
				}
			}
			if(($index + 1) % 10 != 0 ){
				sleep(1);
			}else{
				sleep(300);
			}
			
		}
    }
	function isValidEmail($email) {
	    //dd((filter_var($email, FILTER_VALIDATE_EMAIL)), $email);
	    return (filter_var($email, FILTER_VALIDATE_EMAIL));
	}
}