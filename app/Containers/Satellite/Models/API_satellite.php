<?php

namespace App\Containers\Satellite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class API_satellite extends Model
{
	use SoftDeletes;

    protected $table="api_satellite";
    protected $fillable=['id','domain','owner','created_at','updated_at','secret_key','client_id','client_secret', 'credit_id'];

    protected $dates = ['deleted_at'];

	public function owner(){
	    return $this->hasOne('App\Containers\Satellite\Models\API_owner','id','owner');
	}
	public function user(){
	    return $this->hasOne('App\Containers\User\Models\User','id','id');
	}
	public function currencies(){
	    return $this->belongsToMany('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','api_users_currencies','iduser','idcurrency');
	}
	public function searchEngines(){
	    return $this->belongsTo('App\Containers\Configuration\Models\API_search_engine','search_engine');
	}
	public function token(){
	    return $this->hasOne('App\Containers\UltraApi\Models\SatelliteToken','id_satelite','id');
	}
	public function credit(){
		return $this->hasOne('App\Containers\Freelance\Models\FreelanceCreditModel', 'satellite_id', 'id');
	}
	public function bankinfo(){
		return $this->hasMany('App\Containers\Freelance\Models\FreelanceBankInfo', 'id', 'satellite_id');
	}
	public function features(){
		return $this->hasOne('App\Containers\Freelance\Models\FreelanceFeaturesModel', 'id', 'satellite_id');
	}
	public function reviews(){
		return $this->hasMany('App\Containers\Freelance\Models\FreelanceReviewsModel', 'id', 'satellite_id');
	}
}
