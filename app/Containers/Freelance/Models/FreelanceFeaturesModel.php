<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceFeaturesModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_features";
  protected $fillable=['id', 'id_freelance', 'exp_years',
          'completed_sales', 'languages', 'skills', 'ranking', 'common_places', 'ranking_plus', 'satellite_id'];
  protected $dates = ['deleted_at'];

  public function freelance(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceModel', 'id_freelance', 'id');
  }
  public function satellite(){
	  return $this->belongsTo('App\Containers\Satellite\Models\API_satellite','satellite_id','id');
  }
}
