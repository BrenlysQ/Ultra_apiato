<?php

namespace App\Containers\Hotusa\Models;

use Illuminate\Database\Eloquent\Model;
class HotusaProvincies extends Model
{
  protected $table="hotusa_provincies";
  protected $connection = 'pumaster';
  protected $fillable=[
    'country_code','province_code','province_name','number_of_hotels'
  ];
  public function country(){
      return $this->belongsTo('App\Containers\Hotusa\Models\HotusaCountries','country_code','country_code');
  }
}
