<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Hotusa\Models\HotusaCountries;

/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetDirectoriesTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($provincie_code)
    {
      $data = (object) array (
        "category" => Input::get('category'),
        "lenguage" => Input::get('lenguage'),
        "radio" => Input::get('radio'),
      );

      $request = '<?xml version="1.0" encoding="ISO-8859-1" ?>
                  <!DOCTYPE peticion SYSTEM "http://xml.hotelresb2b.com/xml/dtd/pet_directorio.dtd">
                  <peticion>
                    <tipo>701</tipo>
                    <nombre>Hotel Directory request</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <parametros>
                      <hotel/>
                      <pais>'.$provincie_code.'</pais>
                      <poblacion/>
                      <provincia/>
                      <categoria></categoria>
                      <usuario>'.getenv('CODIGO_USU').'</usuario>
                      <afiliacion>'.getenv('AFILIACION_HOT').'</afiliacion>
                      <servhot1/>
                      <servhot2/>
                      <servhot3/>
                      <servhab1/>
                      <servhab2/>
                      <servhab3/>
                      <idioma>'.$data->lenguage.'</idioma>
                      <radio>'.$data->radio.'</radio>
            <marca/>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
