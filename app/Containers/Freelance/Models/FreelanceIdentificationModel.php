<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceIdentificationModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_identification";
  protected $fillable=['id', 'id_freelance', 'image', 'identification'];
  protected $dates = ['deleted_at'];

  public function freelance(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceModel', 'id_freelance', 'id');
  }
}
