<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaMediaModel extends Model
{
  protected $table="insta_media";
  protected $fillable=[
    'media_id','url_img'
  ];
  public function comments(){
    return $this->hasMany('App\Containers\Instagram\Models\InstaCommentsModel','media_id','id');
  }
  public function likes(){
    return $this->hasMany('App\Containers\Instagram\Models\InstaLikesModel','media_id','id');
  }
}
