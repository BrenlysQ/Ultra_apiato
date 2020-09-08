<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceCampaignsModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_campaigns";
  protected $fillable=['id','init_plan','end_plan','status','created_by', 'id_freelance', 'data'];
  protected $dates = ['deleted_at'];

  public function createdBy(){
    return $this->belongsTo('App\Containers\User\Models\User', 'created_by', 'id');
  }

  public function freelance(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceModel', 'id_freelance', 'id');
  }
}
