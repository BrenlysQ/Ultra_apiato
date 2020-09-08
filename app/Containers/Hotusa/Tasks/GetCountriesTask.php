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
class GetCountriesTask extends Task
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
      $request = '<?xml version="1.0" encoding="iso-8859-1"?>
                  <peticion>
                    <nombre>Petición de Paises</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <tipo>5</tipo>
                    <idioma>2</idioma>
                  </peticion>';
      return $request;
    }

}
