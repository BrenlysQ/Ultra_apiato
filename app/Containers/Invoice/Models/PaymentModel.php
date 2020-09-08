<?php

namespace App\Containers\Invoice\Models;
use App\Ship\Parents\Models\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class PaymentModel extends Model
{
  protected $table = "puf_administration_payments";
  protected $fillable = [
    'invoices', 'id_freelance'
  ];

  public function invoice(){
    return $this->hasMany('App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel', 'payment_id', 'id' );
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
