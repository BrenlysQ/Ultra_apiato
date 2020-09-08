<?php

namespace App\Containers\UltraApi\Models;

use Illuminate\Database\Eloquent\Model;

class SatelliteToken extends Model
{
    protected $table="satellite_tokens";
  	protected $fillable=['id','id_satelite','valid_until','access_token','refresh_token'];

  	public function satellite(){
	    return $this->belongsTo('App\Containers\Satellite\Models\API_satellite','id_satelite','id');
	}

}
