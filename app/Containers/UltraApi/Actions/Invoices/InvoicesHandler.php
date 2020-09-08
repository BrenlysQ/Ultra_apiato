<?php
namespace App\Containers\UltraApi\Actions\Invoices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use Mail;
use App\Mail\InvoiceMail;
use App\Containers\Satellite\Actions\SatelliteHandler;
use App\Containers\Invoice\Models\ItemsModel;
use App\Containers\UltraApi\Tasks\EmitItemsTask;


class InvoicesHandler{
  public static function Get($id){
	//dd($id);
    $invoice = InvoiceModel::where('id',$id)
                  ->with('satellite')
                  ->with('pgateway')
                  ->with('currency_data')
                  ->with('items')
                  ->first();
    if($invoice){
      return $invoice;
    }
    die("Invalid invoice");
  }
  public static function HasPermission($id){
    echo $id; die;
  }
  public static function VerifyPermission($req)
  {
    //$sign = 'eyJpdiI6InFTaXRMWExTTWJZWXVBYmRKNTA1bUE9PSIsInZhbHVlIjoiM0hNZEJGZ05VZTJWNHArUGFjZ08xbDVnazFydzlqOHRrdHUrcmF2YTBCd2lEVkd3TEFaRXFrTFVKeUQ1ZVlNdWVQMzRCbU1wQ3o1Q0RtOThaTkI2MnpKZjdxTXRab3ZTWmhpS2NvejU5eHc9IiwibWFjIjoiNDhlNzc3Y2QzOTZmNDUzOGRlYTc3NjAxMjg4ZTQ2YjBjN2RlMjJhMDM2YzE1MDVjOTUwOTA4MjU0NjdjZjA0ZCJ9';
    $invid = $req->get('invoice');
    $sign = $req->get('sign',false);
    $invoice = InvoicesHandler::Get($invid); //OBTENGO EL MODELO DE LA FACTURA QUE EL USER DESEA VISUALIZAR
    //Si la factura esta firmada por el usuario que esta logueado la devuelvo
    if($invoice->signed_by == Auth::id()){
      return $invoice;
    }elseif(empty($invoice->signed_by)){
      $secret = (object) SatelliteHandler::GetSecret($invoice->satelite);
      $newEncrypter = new \Illuminate\Encryption\Encrypter( base64_decode($secret->secret_key), config( 'app.cipher' ) );
      $decrypted = json_decode($newEncrypter->decrypt( $sign ));
      if($invoice->usersatid == $decrypted->id){
        $invoice->signed_by = Auth::id();
        $invoice->save();
        return $invoice;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
  public static function New($data){
    return new InvoiceModel($data);
  }

  public static function NewItems($data){
    return new ItemsModel($data);
  }


  public static function MkPayment($payment){
    $invoice = $payment->invoice;
    if( $invoice->total_paid < $invoice->total_amount){
      $payment->processedby = Auth::id();
      $invoice->total_paid = $invoice->total_paid + $payment->amount;
      $invoice->load('satellite');
      if($invoice->total_paid >= $invoice->total_amount){
        $invoice->load('items');
        $invoice->st = 1;
        $response = (new EmitItemsTask)->run($invoice);
      }
    }
    $invoice->save();
  }
}
?>
