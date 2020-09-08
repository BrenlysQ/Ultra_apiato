<?php
namespace App\Containers\Kiu\Actions;
use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Actions\Action;
use App\Containers\Itineraries\Tasks\RequestModifyPnr;
use App\Containers\Kiu\Tasks\MakeRequestTask;
class KiuPnr extends Action{
  public static function Create($data)
  {
    $xml = (new RequestModifyPnr())->run($data);
	$response = (new MakeRequestTask())->run($xml);
    print_r ($response); die;
  }
}
?>
