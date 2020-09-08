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
class CancelBookingTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($n_localizador)
    {
      $request = '<?xml version="1.0" encoding="ISO-8859-1" ?>
                  <!DOCTYPE peticion SYSTEM " http://xml.hotelresb2b.com/xml/dtd/pet_reserva.dtd">
                  <peticion>
                    <nombre>Anulación/Confirmación de Reserva</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <tipo>3</tipo>
                    <parametros>
                      <localizador>'.$n_localizador.'</localizador>
                      <accion>AI</accion>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
