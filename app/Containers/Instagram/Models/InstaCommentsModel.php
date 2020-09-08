<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaCommentsModel extends Model
{
  protected $table="insta_comments";
  protected $fillable=[
    'comment_id',
    'comment',
    'user_id',
    'user',
    'timestamp_comment',
    'media_id',
    'status_id'
  ];
  public function media(){
  	return $this->belongsTo('App\Containers\Instagram\Models\InstaMediaModel');
  }
  public function message(){
    return $this->belongsTo('App\Containers\Instagram\Models\InstaMessagesModel');
  }
  public function status(){
  	return $this->hasOne('App\Containers\Instagram\Models\InstaStatusModel', 'status_id', 'id');
  }
}
 
