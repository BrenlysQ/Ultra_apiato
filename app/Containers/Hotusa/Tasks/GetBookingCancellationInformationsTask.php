<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

class GetBookingCancellationInformationsTask extends Task
{
    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($lins,$codigo_cobol)
    {

      $request = '<!DOCTYPE peticion SYSTEM "http://xml.hotelresb2b.com/xml/dtd/pet_algGastosCanc.dtd">
                  <peticion>
                    <tipo>144</tipo>
                    <nombre>Gastos de cancelacion de reservas</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <parametros>
                      <datos_reserva>
                      <hotel>'.$codigo_cobol.'</hotel>
									  ';
					  if (is_array($lins)){
                        foreach($lins as $lin){
                          $request .= '
                          <lin>' . $lin . '</lin>';
                        }
                      }else{
                        $request .= '
                          <lin>' . $lins . '</lin>';
                      }

					  $request .='
                      </datos_reserva>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
