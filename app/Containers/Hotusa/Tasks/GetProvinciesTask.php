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
class GetProvinciesTask extends Task
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
                    <nombre>Petición de Provincias</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <tipo>6</tipo>
                  </peticion>';
      return $request;
    }

}
