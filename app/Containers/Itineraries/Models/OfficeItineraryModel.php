<?php

namespace App\Containers\Itineraries\Models;
use App\Ship\Parents\Models\Model;

class OfficeItineraryModel extends Model
{
  protected $table = "office_itineraries";
  protected $fillable = [
    'id','localizable', 'currency', 'seller_email', 'status'
  ];
  protected $dates = ['deleted_at'];

  public function status(){
    return $this->hasOne('App\Containers\Itineraries\Models\ItinerarieStatusModel','id','status');
  }
}
