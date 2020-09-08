<?php
namespace App\Commons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;

class Airports{
  public $data;
  public function __construct($code){
    $res = Airports::GetInfo($code);
    $this->data = $res[0];
  }
  public function BasicInfo(){
    return (object) array(
      "IATA" => $this->data->IATA,
      "name" => $this->data->name
    );
  }
  private static function GetInfo($code = false){
    $airport = Cache::remember('AIS-' . $code, 1, function() use ($code)
    {
      if($code){
        return DB::connection('pumaster')->select("SELECT * FROM airports WHERE IATA = '" . $code . "'");
      }
    });
    return $airport;
  }
}
?>
