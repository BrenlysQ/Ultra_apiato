<?php
namespace App\Containers\Satellite\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Satellite\Models\API_satellite;
use Illuminate\Support\Facades\Hash;
use App\Containers\Configuration\Models\API_search_engine_satellite;
use App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel;
use App\Containers\UltraApi\Actions\Currencies\Models\UserCurrencyModel;
use App\Containers\User\Models\User;
use App\Containers\User\Data\Repositories\UserRepository;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Containers\User\Actions\RegisterUserAction;
use App\Containers\User\Actions\UpdateUserAction;
use App\Containers\User\Tasks\UpdateUserTask;
use App\Containers\UltraApi\Tasks\SatelliteTokenTask;
use App\Containers\UltraApi\Tasks\AddSatelliteTokenTask;
use App\Containers\UltraApi\Tasks\UpdateSatelliteTokenTask;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;


class SatelliteHandler extends Action{

  public static function CreateSatellite(){
    $action = new RegisterUserAction();
    $data = (object) array (
      "email" => Input::get('email_satellite'),
      "name" => Input::get('name'),
      "password" => Input::get('password'),
      "birth" => '',
      "gender" => ''
    );

    $user = $action->run($data);
    $currencies = Input::get('moneda');
    $search_engines = Input::get('search_engine');
    foreach ($search_engines as $search_engine) {
      $user->search_engines()->attach($search_engine);
    }

    foreach ($currencies as $currency) {
      $user->currencies()->attach($currency);
    }

    $domain_satellite = Input::get('domain');
    $client_id = Input::get('client_id',0);
    $client_secret = Input::get('client_secret',0);
    $name_owner = Input::get('name_responsable');
    $identification = Input::get('identification');
    $email_owner = Input::get('email_responsable');
    $address_owner = Input::get('address');
    $telephone_owner = Input::get('telephone');
    $key = base64_encode(random_bytes(32));
    $data = (object) array(
      "name" => $name_owner,
      "identification" => $identification,
      "email" => $email_owner,
      "address" => $address_owner,
      "telephone" => $telephone_owner,
    );
    $id = OwnerHandler::createOwner($data);
    $satellite = new API_satellite();
    $satellite->domain = $domain_satellite;
    $satellite->owner = $id;
    $satellite->id = $user->id;
    $satellite->secret_key = $key;
    $satellite->client_id = $client_id;
    $satellite->client_secret = $client_secret;
    $satellite->save();

    //Se le crea el Token al sat
    // $response = (new SatelliteTokenTask())->run(json_decode($satellite));
    // (new AddSatelliteTokenTask())->run($response,$satellite);

    return json_encode($response);
  }
  public static function GetSecret($id = false)
  {
    if(!$id){
      $id = Auth::id();
    }
    $satellite = API_satellite::find($id);
    return array(
  		'secret_key' => $satellite->secret_key,
  		'sat_id' => $id
    );
  }
  public static function updatingSatellite($gtSatellite){
      
      $userData = array (
        "id" => $gtSatellite->user->id,
        "email" => Input::get('email_satellite'),
        "name" => Input::get('name'),
        "birth" => '',
        "gender" => ''
      );

      $user = User::find($gtSatellite->user->id);
      $user->update($userData);
      UserCurrencyModel::where('iduser',$gtSatellite->user->id)->delete();
      API_search_engine_satellite::where('iduser',$gtSatellite->user->id)->delete();

      $currencies = Input::get('currency_selected');
      if (is_array($currencies) ||  is_object($currencies)) {
        foreach ($currencies as $currency) {
          $user->currencies()->attach($currency['id']);
        }
      }

      $search_engines = Input::get('search_engine');
      if (is_array($search_engines) ||  is_object($search_engines)) {
        foreach ($search_engines as $search_engine) {
          $user->search_engines()->attach($search_engine['id']);
        }
      }

      $domain_satellite = Input::get('domain');
      $client_id = Input::get('client_id',0);
      $client_secret = Input::get('client_secret',0);
      $name_owner = Input::get('name_responsable');
      $identification = Input::get('identification');
      $email_owner = Input::get('email_responsable');
      $address_owner = Input::get('address');
      $telephone_owner = Input::get('telephone');

      $data = (object) array(
        "id" => $gtSatellite->owner->id,
        "name" => $name_owner,
        "identification" => $identification,
        "email" => $email_owner,
        "address" => $address_owner,
        "telephone" => $telephone_owner
      );

      OwnerHandler::updateOwner($data,$gtSatellite->owner->id);

      $satellite = API_satellite::find($gtSatellite->id);
      $satellite->domain = $domain_satellite;
      $satellite->client_id = $client_id;
      $satellite->client_secret = $client_secret;
      $satellite->save();

      //Se le crea el Token al sat
      //$respuesta = $this->call(SatelliteTokenTask::class,[$satellite]);
      // $response = (new SatelliteTokenTask())->run(json_decode($satellite));
      // (new UpdateSatelliteTokenTask())->run($response,$satellite);
      // return json_encode($response);
  }


  public static function updatingSatellitePassword($id){
      $user = User::findOrFail($id);
      $password = Input::get('password');
      $user->fill([
            'password' => Hash::make($password)
        ])->save();
  }

  public static function listSatellites(){
    return API_satellite::with('user', 'owner')->get()->toJson();
  }

  public static function deleteSatellite($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    $satellite = API_satellite::find($id);
    dd($satellite);
    if ($satellite->trashed()){ //IF THE MODEL IS TRASEHD< SO DELETE IT FOREVER
      $satellite->forceDelete();
    }else{ //ELSE PLEASE SOFTDELETE IT :-)
      $satellite->delete();
    }
  }

  public static function listDeletedSatellites(){
    return API_satellite::onlyTrashed()->with('user', 'owner')->get()->toJson();
  }

  public static function GetSatellite($id){
    $satellite = API_satellite::where('id',$id)
      ->with('owner')
      ->first();

    $satellite->load(['user' => function($q) {
          $q->with('currencies','search_engines');
        }]);
    return $satellite;
  }

  public static function GetByDomain($domain){
    $satellite = API_satellite::where('domain',$domain)
      ->with('owner')
      ->first();
    return $satellite;
  }

  public static function getSatelitesBalance(){
   $usd = 0;
   $bsf = 0;
   $satellites = API_satellite::with('user', 'owner')->get();
   foreach ($satellites as $satellite) {
     $invoicesusd = InvoiceModel::where('administration_status',1)
                             ->where('satelite', $satellite->id)
                             ->where('currency', 4)
                             ->get();
     foreach ($invoicesusd as $invoiceusd) {
       $usd += $invoiceusd->total_fee;
     }
     $satellite->usd = $usd;
     $invoicesbsf = InvoiceModel::where('administration_status',1)
                             ->where('satelite', $satellite->id)
                             ->where('currency', 3)
                             ->get();
     foreach ($invoicesbsf as $invoicebsf) {
       $bsf += $invoicebsf->total_fee;
     }
     $satellite->bsf = $bsf;
     $bsf = 0;
     $usd = 0;
   }
   $response = json_decode($satellites);
   return $response;
 }

 public static function getSatelliteInvoices(){
   $id = Input::get('id');
   $invoices = InvoiceModel::where('satelite', $id)
                            ->where('administration_status', 1)
                            ->get();
    return $invoices;

 }
}
