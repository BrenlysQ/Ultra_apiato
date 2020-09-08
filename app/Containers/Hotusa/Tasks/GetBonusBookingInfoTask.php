<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetBonusBookingInfoTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($data)
    {
      $request = '<?xml version="1.0" encoding="ISO-8859-1"?>
                  <!DOCTYPE peticion SYSTEM "http://xml.hotelresb2b.com/xml/dtd/pet_bono.dtd">
                  <peticion>
                  <tipo>12</tipo>
                  <nombre>Peticion de Bono</nombre>
                  <agencia>HOTUSA</agencia>
                    <parametros>
                      <idioma>'.$data->language.'</idioma>
                      <localizador>'.$data->booking_id.'</localizador>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
