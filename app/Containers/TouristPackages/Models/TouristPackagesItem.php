<?php

namespace App\Containers\Hotusa\Models;

use Illuminate\Database\Eloquent\Model;
class HotusaBooking extends Model
{
  protected $table="api_tourist_packages";
  protected $fillable=[
    
  ];
  public function item()
  {
      return $this->morphOne('App\Containers\Invoice\Models\ItemsModel', 'invoiceable');
  }

}
