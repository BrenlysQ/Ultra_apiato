<?php
namespace App\Commons;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;

class Airline extends Action{
  public $data;
  public function __construct($code){
    $res = Airline::GetInfo($code);
    $this->data = $res[0];
  }
  public function BasicInfo(){
    return (object) array(
      "iata" => $this->data->iata,
      "name" => $this->data->name
    );
  }
  private static function GetInfo($code = false){
    $airline = Cache::remember('AL-' . $code, 2000, function() use ($code)
    {
      if($code){
        return DB::connection('pumaster')->select("SELECT * FROM airlines WHERE iata = '" . $code . "'");
      }
    });
    return $airline;
  }
}
?>
