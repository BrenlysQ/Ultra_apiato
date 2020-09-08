<?php

namespace App\Containers\Itineraries\Models;
use App\Ship\Parents\Models\Model;

class ItinerarieStatusModel extends Model
{
  protected $table = "api_itineraries_status";
  protected $fillable = [
    'id','status_name'
  ];

  public function itineraries(){
    return $this->hasMany('App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel', 'status', 'id' );
  }
}
