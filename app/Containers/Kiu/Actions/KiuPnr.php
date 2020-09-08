<?php
namespace App\Containers\Kiu\Actions;
use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Actions\Action;
use App\Containers\Itineraries\Tasks\RequestModifyPnr;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\Kiu\Tasks\MakeRequestTask;
class KiuPnr extends Action{
  public static function Create($data)
  {
	 //echo 'HOLa'; die;
    $xml = (new RequestModifyPnr())->run($data);  
    $response = (new MakeRequestTask())->run($xml);
    if($response->Success == null){
      $response->modified = true;
      $data->update();
    }else{
	  	$response->modified = false;
    }
    return $response;
  }
}
?>
