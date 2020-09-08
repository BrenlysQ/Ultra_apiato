<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;

class FreelanceModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance";
  protected $fillable=['id', 'id_satellite', 'name', 'lastname',
                      'email', 'phone', 'city', 'country', 'latitude',
                      'longitude', 'image', 'facebook', 'twitter', 'instagram',
                      'gender', 'website', 'team'];
  protected $dates = ['deleted_at'];

  public function features(){
    return $this->hasOne(FreelanceFeaturesModel::class, 'id_freelance', 'id');
  }

  public function reviews(){
    return $this->hasMany('App\Containers\Freelance\Models\FreelanceReviewsModel', 'id_freelance', 'id');
  }

  public function satellite(){
    return $this->belongsTo('App\Containers\Satellite\Models\API_satellite', 'id_satellite');
  }

  public function bankinfo(){
    return $this->hasOne('App\Containers\Freelance\Models\FreelanceBankInfoModel', 'id_freelance', 'id');
  }

  public function team(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceTeamModel', 'team', 'id');
  }

  public function confirmcode(){
    return $this->hasOne('App\Containers\Freelance\Models\FreelanceCodeModel', 'id_freelance', 'id');
  }
  
  public function identification(){
    return $this->hasOne('App\Containers\Freelance\Models\FreelanceIdentificationModel', 'id_freelance', 'id');
  }
}
