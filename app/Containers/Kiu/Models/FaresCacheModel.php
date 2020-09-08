<?php

namespace App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;

class FaresCacheModel extends Model
{
  protected $table="kiu_fares_cache";
  protected $fillable=[
    'expirationdate','footprint','passengertype','route',
    'class','totalfare','currency','airpricinginfo'];
  public function currency(){
    return $this->hasOne('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','id','idcurrency');
  }
  public function GetAirpricinginfoAttribute($value){
    return json_decode($value);
  }
}
