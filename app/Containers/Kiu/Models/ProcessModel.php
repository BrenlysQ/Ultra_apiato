<?php

namespace  App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessModel extends Model
{
  use SoftDeletes;

  protected $table="kiu_process";
  protected $fillable=['id', 'process', 'order', 'status'];
  protected $dates = ['deleted_at'];

  
  public function next(){
    return ProcessModel::where('order', '>', $this->order)->orderBy('order','asc')->first();
  }
}
