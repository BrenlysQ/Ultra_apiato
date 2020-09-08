<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaLikesModel extends Model
{
  protected $table="insta_likes";
  protected $fillable=[
    'user_id','user','timestamp_like','media_id'
  ];

  public function media(){
  	return $this->belongsTo('App\Containers\Instagram\Models\InstaMediaModel');
  }
}
