<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceBankInfoModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_bank_info";
  protected $fillable=['id', 'id_freelance', 'identification','routing_number','account_number',
                            'bank_name', 'account_type', 'account_owner', 'satellite_id'];
  protected $dates = ['deleted_at'];

  public function freelance(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceModel', 'id_freelance', 'id');
  }
  public function satellite(){
    return $this->belongsTo('App\Containers\Satellite\Models\API_satellite','satellite_id','id');
  }
}
