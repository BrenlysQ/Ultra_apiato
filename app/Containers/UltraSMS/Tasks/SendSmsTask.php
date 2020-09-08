<?php

namespace App\Containers\UltraSMS\Tasks;
use Mail;
use App\Ship\Parents\Tasks\Task;
use App\Containers\UltraSMS\Models\UltraSMS;
use App\Containers\UltraSMS\Tasks\GetDataSmsTask;
use Illuminate\Support\Facades\Input;

/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class SendSmsTask extends Task
{
    public function run() {

        $data = (new GetDataSmsTask)->run();
        foreach($data as $key){
        	$tokenSms["u"]  = "demo";
			$tokenSms["t"]  = "D3M04P1";
			$tokenSms["cel"] = $key->telf;
			$text = Input::get('mensaje');
			$max = strlen($text);
			if($max <= 160){
				$text = substr($text,0,160);
				$tokenSms["men"] = str_replace(" ","_",$text);
				$request= json_decode(file_get_contents('http://mensajesms.com.ve/sms2/API/api.php?cel='.$tokenSms["cel"].'&men='.$tokenSms["men"].'&u='.$tokenSms["u"].'&t='.$tokenSms["t"].'&tr=json'));
				//print_r($request->salida);
				// print_r('http://mensajesms.com.ve/sms2/API/api.php?cel='.$tokenSms["cel"].'&men='.$tokenSms["men"].'&u='.$tokenSms["u"].'&t='.$tokenSms["t"].'&tr=json'.'<br /><br />');
				if($request->salida == "Mensaje Enviado"){
					$response["estado_sms"]="enviado_sms";
					$response["estado"]="notificado";
					$response["mensaje"]=" Se envio el SMS con la Actividad";
					print_r($response['mensaje'].'<br/ ><br/ >');
				}else{
					$response["mensaje"]="Motivo de rechazo al envio: ".$request->salida;
					$response["datos"]="";
					$response["estado"]="false";
					print_r($response['mensaje'].'<br/ ><br/ >');
                    return array('error' => 'El servidor no ha respondido.');
				}
                return array('success' => 'El mensaje fue enviado correctamente');
			}else{
				return array('error' => 'El texto tiene mas de 160 caracteres y no podra ser enviado completo');
			}
        }
    }

}
