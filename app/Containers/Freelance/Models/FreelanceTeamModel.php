<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceTeamModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_teams";
  protected $fillable=['id', 'name', 'partners_limit', 'satellite_owner'];
  protected $dates = ['deleted_at'];

  public function freelances(){
    return $this->hasMany('App\Containers\Freelance\Models\FreelanceModel', 'team', 'id');
  }

  public function owner(){
    return $this->belongsTo('App\Containers\Satellite\Models\API_satellite' ,'satellite_owner');
  }

}
