<?php

namespace App\Containers\UltraApi\Actions\Itineraries\Models;

use App\Ship\Parents\Models\Model;

class ItinModel extends Model
{
  protected $table = "api_itineraries";
  protected $fillable = [
    'itinerary_id','origin','destination','date_return','paxes',
    'date_departure','odo','itinerary','usersatdata','usersatid','satelite',
    'status'
  ];
  //protected $dates = ['deleted_at'];
  public function item()
  {
      return $this->morphOne('App\Containers\Invoice\Models\ItemsModel', 'invoiceable');
  }
  public function satellite_main(){
      return $this->hasOne('App\Containers\User\Models\User','id','satelite');
  }
  public function satellite(){
      return $this->hasOne('App\Containers\Satellite\Models\API_satellite','id','satelite');
  }
  public function getOdoAttribute($value)
  {
  	return json_decode($value);
  }
  public function getItineraryAttribute($value)
  {
    return json_decode($value);
  }
  public function getPaxesAttribute($value)
  {
    return json_decode($value);
  }
  public function getUsersatdataAttribute($value)
  {
    return json_decode($value);
  }
  public function status(){
    return $this->hasOne('App\Containers\Itineraries\Models\ItinerarieStatusModel','id','status');
  }
}
