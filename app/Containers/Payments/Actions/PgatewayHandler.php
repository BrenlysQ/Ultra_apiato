<?php

namespace App\Containers\Payments\Actions;
use App\Ship\Parents\Actions\Action;

use Illuminate\Support\Facades\Log;
use App\Containers\Payments\Models\PgateWayModel;
use App\Containers\Payments\Gateways\CreditCard\CreditCardHandler;
use App\Containers\Payments\Gateways\PortalMercantil\MercantilHandler;
use App\Containers\Payments\Gateways\BankTransfer\BankTransferHandler;
use Illuminate\Support\Facades\View;
class PgatewayHandler extends Action{
  private static function GetConf($gateway){
    $handler = PgatewayHandler::GetObject($gateway->route);
    return $handler::GetConf();
  }
  public static function GetInfo($gatewayid){
    $gateway = PgateWayModel::findOrfail($gatewayid);
    return (object) array(
      "configuration" => PgatewayHandler::GetConf($gateway),
      "info" => json_decode($gateway->tojson())
    );
  }
  public static function GetForm($gateway, $data){
    $pgateway = PgateWayModel::findOrfail($gateway);
    $handler = PgatewayHandler::GetObject($pgateway->route);
    return $handler->RenderForm($gateway, $data);
  }
  private static function GetObject($route){
    switch ($route) {
      case "creditcard":
        $handler = new CreditCardHandler();
        break;
      case "mercantil":
        $handler = new MercantilHandler();
        break;
      case "banktransfer":
        $handler = new BankTransferHandler();
        break;
    }
    return $handler;
  }
  //GETTING TABS TO BOOKING WINDOW
  public static function GetTabs($gateways, $data = false){
    $ret = array();
    foreach ($gateways as $gateway) {
      $handler = PgatewayHandler::GetObject($gateway->route);
      //$ret[] = $handler->GetTab($gateway);
      array_push($ret, (object) $handler->GetTab($gateway, $data));
    }
    return (object) $ret;
  }
}
