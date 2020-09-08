<?php
namespace App\Containers\UltraApi\Actions\Fees;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Fees\Models\FeeModel;
class FeesHandler{

  public static function StoreFee(){
    $feeamount = Input::get('fee');
    $plusultra = Input::get('plusultra',0);
    $type = Input::get('type');
    $currency = Input::get('currency');
    $userid = Input::get('user');
    $name = Input::get('name');
    $act = Input::get('act','store');
    if($act == 'store'){
      $fee = new FeeModel();
    }else{
      $id = Input::get('id');
      $fee = FeeModel::findOrFail($id);
    }
    $fee->name = $name;
    $fee->fee = $feeamount;
    //$fee->plusultra = $plusultra;
    //$user = Auth::user();
    /*if($user->hasRole('admin')) {
      $fee->plusultra = 1;
      $fee->user = $userid;
    }else{
      $fee->plusultra = 0;
      $fee->user = Auth::id();
    }*/
    $fee->plusultra = $plusultra;
    $fee->user = $userid;
    $fee->type = $type;
    $fee->currency = $currency;
    $fee->save();
  }
  public static function ListFee(){
    $user = Auth::user();
    if($user->hasRole('admin')){
      return FeeModel::with('currency')
                      ->with('user')
                      ->get()
                      ->tojson();
    }else{
      return FeesHandler::GetFeesByUser(Auth::id());
    }
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
  public static function FeeDelete($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA

    $fee = FeeModel::findOrfail($id);
    if ($fee->trashed()){ //IF THE MODEL IS TRASEHD< SO DELETE IT FOREVER
      $fee->forceDelete();
    }else{ //ELSE PLEASE SOFTDELETE IT :-)
      $fee->delete();
    }
  }
  public static function GetFee($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    $bank = FeeModel::where('id',$id)->with('currency')->first()->tojson();
    //print_r($bank->tojson()); die;
    return $bank;
  }

  public static function GetFeeByCurrencyCode($currency){
    $feesat = FeeModel::join('api_currencies',
              'api_user_fee.currency','=','api_currencies.id'
            )
            ->select([
              'api_user_fee.type',
              'api_user_fee.user',
              'api_user_fee.fee',
            ])
            ->whereRaw("api_currencies.code = '" . $currency . "'
            AND (api_user_fee.user = " . Auth::id() . " AND api_user_fee.plusultra = 0)")
            ->get()->tojson();
    $feeplus = FeeModel::join('api_currencies',
              'api_user_fee.currency','=','api_currencies.id'
            )
            ->select([
              'api_user_fee.type',
              'api_user_fee.user',
              'api_user_fee.fee',
            ])
            ->whereRaw("api_currencies.code = '" . $currency . "'
            AND (api_user_fee.user = " . Auth::id() . " AND api_user_fee.plusultra = 1)")
            ->get();
    //echo count($feeplus); die;
    if(count($feeplus) < 1){
      $feeplus = FeeModel::join('api_currencies',
                'api_user_fee.currency','=','api_currencies.id'
              )
              ->select([
                'api_user_fee.type',
                'api_user_fee.user',
                'api_user_fee.fee',
              ])
              ->whereRaw("api_currencies.code = '" . $currency . "'
              AND (api_user_fee.user = 1 AND api_user_fee.plusultra = 1)")
              ->get();
    }
    $ret = '{
      "ownfees" : ' . $feesat . ',
      "plusfees" : ' . $feeplus . '
    }';
    return json_decode($ret);
  }
}
?>
