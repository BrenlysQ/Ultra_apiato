<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;

class GetCoverage extends Task
{
    public function run()
    {
	  $plan_id = Input::get('plan_id');	
	  $data = array('body'=>'
	  {
	    "planid": "'.$plan_id.'"
	  }');
	  return $data;
    }

}
