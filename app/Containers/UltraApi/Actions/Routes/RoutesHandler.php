<?php
namespace App\Containers\UltraApi\Actions\Routes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Kiu\Models\KiuRoutesModel;
use App\Containers\Kiu\Models\KiuOperation;
class RoutesHandler{

  public static function Store($request){

    //$currencies = Input::get('currencies'); //Currencies Array coming from post
    $currencies = array(3,4);
    $origin = Input::get('origin');
    $destination = Input::get('destination');
    $direct = Input::get('direct',0);
    $footprint = $origin . '/' . $destination;
    $act = Input::get('act','store');
    if($act == 'store'){
      $route = new KiuRoutesModel();
    }else{
      $id = Input::get('id');
      $route = KiuRoutesModel::findOrFail($id);
    }
    $route->origin = $origin;
    $route->destination = $destination;
    $route->direct = $direct;
    $route->footprint = $footprint;
    $route->save();
    if($act == 'store'){
      foreach($currencies as $currency){
        // if($trans = $route->operations()->where('currency', $currency)->first()){
        //   $trans->update();
        // }else{
        //   $route->operations()->create(['currency' => $currency]);
        // }
        $route->operations()->create(['currency' => $currency]);
      }
    }

    if($policies = json_decode(Input::get('policies'))){
      foreach($policies as $policy){
        $data = [
          'currency' => $policy->currency,
          'airline' => $policy->airline,
          'passenger_type' => $policy->pass_type,
          'classes' => json_encode($policy->classes)
        ];
        //Consulto si la politica existe para esa ruta, con esa aerolinea, para ese tipo de pax, para esa moenda
        // SI existe la politica entonces le hago update
		//dd($policy->stored);
        if(is_numeric($policy->stored)){
          $poli = $route->policies()->find($policy->stored);
		  $poli->update($data);
        }else{ // SIno creo una nueva politica con la data enviada
          $route->policies()->create($data);
        }
      }
    }
  }
  public static function ListRoutes(){
    return KiuRoutesModel::get()->load('policies');
  }
  public static function GetFeesByUser($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    return FeeModel::where('user', '=', $id)
      ->where('plusultra',0)
      ->with('currency')
      ->get()
      ->tojson();
  }
  public static function RouteDelete($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA

    $route = KiuRoutesModel::findOrfail($id);
    if ($route->trashed()){ //IF THE MODEL IS TRASEHD< SO DELETE IT FOREVER
      $route->forceDelete();
    }else{ //ELSE PLEASE SOFTDELETE IT :-)
      $route->delete();
    }
  }
  public static function GetRoute($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    //$bank = KiuRoutesModel::where('id',$id)->with('currency')->first()->tojson();
    //print_r($bank->tojson()); die;
	$route = KiuRoutesModel::findOrFail($id);
	$route->load('policies');
    return $route;
  }


}
?>
