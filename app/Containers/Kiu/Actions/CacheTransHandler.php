<?php
namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Kiu\Models\CacheTrans;
use App\Containers\Kiu\Models\KiuRoutesModel;
use Illuminate\Support\Facades\Cache;
use App\Containers\Kiu\Models\KiuOperation;
class CacheTransHandler extends Action{
  public static function New($operation){
    $currency = $operation->currency;
    $route = $operation->routedata;
    $date_start = date('Y-m-d H:i:s');
    $transaction = new CacheTrans();
    $transaction->route = $route->id;
    $transaction->currency = $currency;
    $transaction->operation = $operation->id;
    $transaction->time_start = $date_start;
    $transaction->save();
    //$transaction->load('kiuroute');
    //$route = $transaction->kiuroute;
    $operation->last_start = $date_start;
    $operation->st = 1;
    $operation->save();
    return $transaction;
  }
  public static function Get($id){
    $transaction = CacheTrans::findOrfail($id);
    $transaction->load('kiuroute');
  $transaction->load('operationdata');
    return $transaction;
  }
  public static function Start($transaction,$outdate,$maxdate){
    $pid = getmypid();
    $transaction->st = 1;
    $transaction->interval_start = $outdate;
    $transaction->interval_end = $maxdate;
    $transaction->pid = $pid;
    $transaction->save();
    $route = $transaction->kiuroute;
    $route->trans_st = 1;
    $route->save();
  }
  public static function End($transaction){
    $date_end = date('Y-m-d H:i:s');
    $transaction->time_end = $date_end;
    $transaction->st = 2;
    $transaction->load('operationdata');
    $operation = $transaction->operationdata;
    $operation->last_end = $date_end;
    $operation->st = 2;
    $operation->save();
    $transaction->save();
  }
  public static function GetNextRoutes($procnumber){
    $operations = KiuOperation::where([
              ['st',2],
              ['currency',4]
            ])
            ->orderBy('last_end')
            ->limit($procnumber)
            ->with('routedata')
            ->get();

    // dd($routes);
    // $retu = array();
    // $routes = KiuRoutesModel::get();
    // foreach($routes as $route){
    //   $res = CacheTransHandler::pichurradas($route);
    //   $retu = array_merge($retu,$res);
    // }
    return $operations;
  }

  // public static function pichurradas($route){
  //   $route->load(['transactions' => function($q) {
  //     $q->where('st',1);
  //   }]);
  //   $retu = array();
  //   if(count($route->transactions) == 0 ){
  //     $retu[] = (object) array('route' => $route,'currency' => 3);
  //     $retu[] = (object) array('route' => $route,'currency' => 4);
  //   }elseif(count($route->transactions) == 1){
  //     ($route->transactions->currency == 3) ? $retu[] = (object) array('route' => $route,'currency' => 4) : $retu[] = (object) array('route' => $route,'currency' => 3);
  //   }
  //   return $retu;
  // }
}
