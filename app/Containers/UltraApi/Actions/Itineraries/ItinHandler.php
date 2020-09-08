<?php
namespace App\Containers\UltraApi\Actions\Itineraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
class ItinHandler{
  public static function NewItin(){

  }
  public static function CurrencyAssign(){
    $currency = Input::get('idcurrency');
    $user = Input::get('iduser');
    DB::table('api_users_currencies')->insert([
        'idcurrency' => $currency,
        'iduser' => $user
        ]);
  }
  public static function StoreCurr(){
    $name = Input::get('name');
    $country = Input::get('country');
    $code = Input::get('code');
    $pcc = Input::get('pcc');
    $act = Input::get('act','store');
    if($act == 'store'){
      $currency = new CurrencyModel();
    }else{
      $id = Input::get('id');
      $currency = CurrencyModel::findOrFail($id);
    }
    $currency->pcc = $pcc;
    $currency->name = $name;
    $currency->country = $country;
    $currency->code = $code;
    $currency->save();
  }
  public static function GetCurrList($default = false){
    $user = Auth::user();
    if($user->hasRole('admin')){
      if(!$default){
        return CurrencyModel::all()->tojson();
      }else{
        return json_decode(CurrencyModel::where('default','=',1)->get()->tojson());
      }
    }else{
      return CurrencyModel::select('api_currencies.*')
      ->join('api_users_currencies',
        'api_currencies.id','=','api_users_currencies.idcurrency'
      )
      ->where('api_users_currencies.iduser', '=', Auth::id())
      ->get()
      ->tojson();
    }
  }
  public static function DeleteCurr($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    $currency = CurrencyModel::find($id);
    if ($currency->trashed()){ //IF THE MODEL IS TRASEHD< SO DELETE IT FOREVER
      $currency->forceDelete();
    }else{ //ELSE PLEASE SOFTDELETE IT :-)
      $currency->delete();
    }
  }
  public static function GetCurrency($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    $bank = CurrencyModel::findOrFail($id)->tojson();
    //print_r($bank->tojson()); die;
    return json_decode($bank);
  }

  /*Itinerarios User Frontend*/

  public static function getUserItineraries($id){
    $intin = ItinModel::where('usersatid',$id)
    ->simplePaginate(4)
    ->load(['item' => function($q) {
      $q->with('invoice');
    }]);
    return $intin;
  }


}
