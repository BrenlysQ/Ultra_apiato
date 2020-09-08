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
class GetHotelsInformationsTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($codigo_cobol,$idioma)
    {
      
      $request = '<?xml version="1.0" encoding="ISO-8859-1"?>
                  <peticion>
                    <tipo>15</tipo>
                    <nombre>INFORMACIÓN DE HOTEL</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <parametros>
                      <codigo>'.$codigo_cobol.'</codigo>
                      <idioma>'.$idioma.'</idioma>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
