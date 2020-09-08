<?php

namespace App\Containers\SabreHotel\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\SabreHotel\Actions\SabreActions;

class ControllerSabre extends ApiController
{

    /**
     * @return  \Illuminate\Http\JsonResponse
     */
    public $sabre;
    public function __construct(){
      $this->sabre = new SabreActions();
    }
    public function getHotelAvailability(){
      return $this->sabre->getAvailability();
    }
    public function getHotelContent(){
      return $this->sabre->getContent();
    }
    public function getHotelList(){
      return $this->sabre->getList();
    }
}
