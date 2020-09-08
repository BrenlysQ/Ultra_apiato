<?php
namespace App\Containers\UltraApi\Actions\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class UsersHandler{
  public static function AssignBank(){
    $bank = Input::get('bank');
    $user = Input::get('user');
    DB::table('api_user_banks')->insert([
        'idbanks' => $bank,
        'iduser' => $user
        ]);
  }
  public static function GetBanksList(){
    $user = Input::get('user',Auth::id());
    $dataTableQuery = DB::table('api_banks')
        ->join('api_currencies',
          'api_banks.idcurrency','=','api_currencies.id'
        )
        ->join('api_user_banks',
          'api_banks.id','=','api_user_banks.idbanks'
        )
        ->select(DB::raw("
          api_banks.id,
          api_banks.name,
          api_banks.idcurrency,
          api_banks.img_url,
          api_banks.account_number,
          api_banks.account_type,
          api_banks.rif,
          api_banks.email,
          api_currencies.name AS currencie_name"))
        ->where([
          ['api_user_banks.iduser', '=', $user],
          ['api_banks.idstatus', '=', 1]
        ])
        ->get();
    return $dataTableQuery;
  }
  public static function GetFee(){
    $fee = Input::get('id');
    $feedata = DB::table('api_users_fee')
        ->join('api_currencies',
          'api_users_fee.currency','=','api_currencies.id'
        )
        ->select(DB::raw("
          api_users_fee.id,
          api_users_fee.type,
          api_users_fee.fee,
          api_users_fee.currency,
          api_currencies.name AS currency_name,
          IF(api_users_fee.type = 1,'Fijo','Porcentaje') AS name"))
        ->where([
          ['api_users_fee.user', '=', Auth::id()],
          ['api_users_fee.id', '=', $fee]
        ])
        ->first();
    if($feedata){
      return json_encode($feedata);
    }else{
      return 'Error';
    }

  }
  public static function SaveFee(){
    $fee = Input::get('fee');
    $currency = Input::get('currency');
    $type = Input::get('type');
    $act = Input::get('act','store');
    if($act == 'store'){
      $inserted = DB::table('api_users_fee')->insertGetId([
          'fee' => $fee,
          'currency' => $currency,
          'user' => Auth::id(),
          'type' => $type
          ]);
      return $inserted;
    }else{
      $id = Input::get('id');
      DB::table('api_users_fee')
        ->where([
          ['id', $id],
          ['user',Auth::id()]
        ])
        ->update([
          'fee' => $fee,
          'currency' => $currency,
          'type' => $type
        ]);
    }
  }
  public static function GetUserFeeList(){
    $dataTableQuery = DB::table('api_users_fee')
        ->join('api_currencies',
          'api_users_fee.currency','=','api_currencies.id'
        )
        ->select(DB::raw("
          api_users_fee.id,
          api_users_fee.type,
          api_users_fee.fee,
          api_currencies.name AS currency_name,
          IF(api_users_fee.type = 1,'Fijo','Porcentaje') AS name"))
        ->where('api_users_fee.user', '=', Auth::id())
        ->get();
    return $dataTableQuery;
  }
}
?>
