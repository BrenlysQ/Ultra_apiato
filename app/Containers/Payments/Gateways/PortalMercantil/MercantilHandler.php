<?php
namespace App\Containers\Payments\Gateways\PortalMercantil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MercantilHandler{
  public static function GetConf($payment){
    return (object) array(
      "onscreen" => false,
      "url" => "http://ssssss.app?id=" . $payment->id
    );
  }
  public static function GetTab($gateway, $data = array()){
    $html = View::make('gateways.mercantil.tab')->render();
    return array(
      "content" => $html,
      "name" => "Portal de pagos",
      "short_name" => "Pagos",
      "id" => $gateway->id
    );
  }
}
?>
