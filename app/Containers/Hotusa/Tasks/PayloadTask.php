<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class PayloadTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run()
    {
    //dd(Input::get('position'));
      $data = (object) array (
        "hotel" => Input::get('hotel',''), // name of code of hotel,no required
        "pais" => Input::get('pais'), // 2 Digits, Country code, required
        "provincia" => Input::get('provincia'), // 5 digits, 2 first the code of the country and then the provincie code, required
        "categoria" => Input::get('categoria',''), // 1 Digit, from 0 t0 5, no required
        "radio" => Input::get('radio'), // 1 Digit, from 0 t0 9, required
        "fechaentrada" => Input::get('fechaentrada'), // mm/dd/aaaa, required
        "fechasalida" => Input::get('fechasalida'),  // mm/dd/aaaa, required
        "edades3" => Input::get('edades3',''), //add ages per kid, sepparate by , no required
        "servhot1" => Input::get('servhot1',''), //2 digits, no required
        "servhot2" => Input::get('servhot2',''), //2 digits, no required
        "servhot3" => Input::get('servhot3',''), //2 digits, no required
        "servhab1" => Input::get('servhab1',''), //2 digits, no required
        "servhab2" => Input::get('servhab2',''), //2 digits, no required
        "servhab3" => Input::get('servhab3',''), //2 digits, no required
        "rooms_data" => Input::get('rooms_data',''), //2 digits, no required
        "idioma" => Input::get('idioma'), //1 digit,1-spanish,2-english,3-french, required
        "duplicidad" => 1, //1 digit,0-yes,1-no, no required
        "tarifas_reembolsables" => Input::get('tarifas_reembolsables',''), //1 digit,0-yes, no required
        "ip" => Input::get('ip'),
        "se" => 12
      );
      return $data;
    }

}
