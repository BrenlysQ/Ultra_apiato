<?php
namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Cache;
use App\Commons\TagsGdsHandler;
use App\Containers\Kiu\Data\TravelOption;
class TOptionsHandler{
  public $found = 0;
  public $topts = array();
  public function Add($data){
    $this->found++;
    $this->topts[] = new TravelOption($data, $this->found);
  }
  private function Cache($payload){
    $identifier = 'BFMKIU-' . str_random(25);
    Cache::put($identifier, json_encode($this->topts), 15);
    return TagsGdsHandler::StoreTag($identifier, $payload);;
  }
  public function GetOpts($payload){
    if(count($this->topts) > 0){
      $tagpu = $this->Cache($payload);
      return (object) array(
        'flights' => $this->topts,
        'tagpu' => $tagpu
      );
    }else{
      return (object) array(
        'errorcode' => 200
      );
    }

  }
}
