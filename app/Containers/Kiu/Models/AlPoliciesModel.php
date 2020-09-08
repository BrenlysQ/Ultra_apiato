<?php

namespace App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;

class AlPoliciesModel extends Model
{
  protected $table="kiu_airline_policies";
  protected $fillable=['passenger_type','classes','idroute','airline','currency'];
  public function route(){
    return $this->hasOne('App\Containers\Kiu\Models\KiuRoutesModel','id','idroute');
  }
  public function getClassesAttribute($value){
    return json_decode($value);
  }
  public function currency(){
    return $this->hasOne('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','id','currency');
  }
}
