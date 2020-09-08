<?php

namespace App\Containers\Payments\Gateways\BankTransfer\Model;

use App\Ship\Parents\Models\Model;

class BankTransferModel extends Model
{
  protected $table = "pup_bank_transfers";
  protected $fillable = [
    'idbank','confirmation'
  ];
  public function payment(){
      return $this->morphOne('App\Containers\Payments\Models\PaymentModel', 'pgatewaydata');
  }
  public function bank(){
    return $this->hasOne('App\Containers\UltraApi\Actions\Banks\Models\ModelBanksTable','id','idbank');
  }
  //protected $dates = ['deleted_at'];
}
