<?php
namespace App\Containers\UltraApi\Actions\Banks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Banks\Models\ModelBanksTable;

class BanksHandler{
  public static function StoreBank(){
    $name = Input::get('name');
    $account_type = Input::get('account_type');
    $account_number = Input::get('account_number');
    $rif = Input::get('rif');
    $email = Input::get('email');
    $idcurrency = Input::get('idcurrency');
    $img_url = Input::get('img_url');
    $act = Input::get('act','store');
    if($act == 'store'){ //IF $act = 'store' THE REQUEST IT'S COMING FROM CREATE NEW FORM
      $bank = new ModelBanksTable();
      $bank->name = $name;
      $bank->idcurrency = $idcurrency;
      $bank->img_url = $img_url;
      $bank->account_number = $account_number;
      $bank->account_type = $account_type;
      $bank->rif = $rif;
      $bank->email = $email;
      $bank->idstatus = 1;
      $bank ->save();
    }else{ //ELSE, REQUEST IT'S COMING FROM UPDATING FORM
      $id = Input::get('id');
      $bank = ModelBanksTable::findOrFail($id);
      $bank->name = $name;
      $bank->idcurrency = $idcurrency;
      $bank->img_url = $img_url;
      $bank->account_number = $account_number;
      $bank->account_type = $account_type;
      $bank->rif = $rif;
      $bank->email = $email;
      $bank ->save();
    }
  }
  public static function GetBanksList($currency = false){
    $user = Auth::user();
    if($user->hasRole('admin')){
      return ModelBanksTable::with('currency')->get()->tojson();
    }else{
      return ModelBanksTable::select('api_banks.*')
      ->with('currency')
      ->join('api_users_currencies',
        'api_users_currencies.idcurrency','=','api_banks.idcurrency'
      )
      ->where([
        ['api_users_currencies.iduser', '=', Auth::id()],
        ['api_users_currencies.idcurrency', '=', $currency]
      ])
      ->get()
      ->tojson();
    }
  }
  public static function GetbyCurrency($currency){
    return ModelBanksTable::where("idcurrency", $currency)->get()->tojson();
  }
  public static function DeleteBank($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA

    $bank = ModelBanksTable::find($id);
    if ($bank->trashed()){ //IF THE MODEL IS TRASEHD< SO DELETE IT FOREVER
      $bank->forceDelete();
    }else{ //ELSE PLEASE SOFTDELETE IT :-)
      $bank->delete();
    }
  }
  public static function GetBank($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    $bank = ModelBanksTable::findOrFail($id)->tojson();
    //print_r($bank->tojson()); die;
    return $bank;
  }
  public static function AssignBank(){
    $bank = Input::get('bank');
    $user = Input::get('user');
    DB::table('api_user_banks')->insert([
        'idbanks' => $bank,
        'iduser' => $user
        ]);
  }
}
?>
