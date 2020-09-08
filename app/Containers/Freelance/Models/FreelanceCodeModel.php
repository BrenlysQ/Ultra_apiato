<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceCodeModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_code";
  protected $fillable=['id', 'id_freelance', 'code'];
  protected $dates = ['deleted_at'];

  public function freelance(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceModel', 'id_freelance', 'id');
  }
}
