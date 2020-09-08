<?php

namespace App\Containers\UltraApi\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
class UapiController extends WebController
{

    public function usitecallback(){
      return 'Hello api';
    }
}
