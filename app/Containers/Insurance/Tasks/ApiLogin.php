<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Insurance\Tasks\MakeRequest;

class ApiLogin extends Task
{
    public function run()
    {
        $data = array('body'=>'{
           "user": "tecnologia@viajesplusultra.com",
           "password": "99ae03"
          }');
        $response = (new MakeRequest())->run('login',$data);
        return $response->body->token;
    }
}
