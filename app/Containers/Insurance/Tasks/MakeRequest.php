<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class MakeRequest extends Task
{
    public function run($route, $data = array('body'=>'[]'),$json = true)
    {
      if(!$json){
		  $response = Curl::to('http://prueba.voucher.interwelt-intl.com/service/' . $route)
        ->withData($data)
        ->withTimeout(0)
        ->post();
	  }else{
		  $response = Curl::to('http://prueba.voucher.interwelt-intl.com/service/' . $route)
        ->withData($data)
        ->withTimeout(0)
        ->asJsonResponse()
        ->post();
	  }
        return $response;
    }

}
