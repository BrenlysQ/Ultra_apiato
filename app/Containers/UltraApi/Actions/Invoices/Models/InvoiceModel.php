<?php

namespace App\Containers\UltraApi\Actions\Invoices\Models;

use App\Ship\Parents\Models\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class InvoiceModel extends Model
{

  use SyncsWithFirebase;

  protected $table = "api_invoices";
  protected $primaryKey = 'id';
  protected $fillable = [
    'total_amount','total_tax','total_paid','total_base','payment_gateway',
    'total_fee','usersatdata','usersatid','satelite','currency','signed_by',
    'contact_pax','feepu', 'administration_status', 'payment_id', 'id_freelance'
  ];

  public function items(){
    return $this->hasMany('App\Containers\Invoice\Models\ItemsModel','invoice','id');
  }
  public function satellite_main(){
      return $this->hasOne('App\Containers\User\Models\User','id','satelite');
  }
  public function getUsersatdataAttribute($value){
    return json_decode($value);
  }
  public function getContactPaxAttribute($value){
    return json_decode($value);
  }
  public function currency_data(){
    return $this->hasOne('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','id','currency');
  }
  public function satellite(){
      return $this->hasOne('App\Containers\Satellite\Models\API_satellite','id','satelite');
  }
  public function pgateway(){
      return $this->hasOne('App\Containers\Payments\Models\PgateWayModel','id','payment_gateway');
  }
  public function status(){
    return $this->hasOne('App\Containers\Invoice\Models\InvoiceStatusModel', 'id', 'administration_status');
  }
  public function review(){
    return $this->hasOne('App\Containers\Freelance\Models\FreelanceReviewsModel', 'id_invoice', 'id');
  }
  public function payments(){
    return $this->hasOne('App\Containers\Invoice\Models\PaymentModel', 'id', 'payment_id');
  }
  public function freelance(){
    return $this->hasOne('App\Containers\Freelance\Models\FreelanceModel', 'id', 'id_freelance');
  }
  protected function getFirebaseSyncData()
    {
      if ($fresh = $this->fresh()) {
          $fresh->load(['items' => function($q) {
                $q->with('invoiceable');
          }]);
          return $fresh->toArray();
      }
    }

}
