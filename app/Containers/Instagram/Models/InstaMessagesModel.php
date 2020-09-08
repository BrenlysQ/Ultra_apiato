<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaMessagesModel extends Model
{
  protected $table="insta_messages";
  protected $fillable=[
	'userid_interaction',
	'item_id',
	'from_user',
	'item_type',
	'timestamp_message',
	'like',
	'threads_id',
	'text_message',
	'comment_id',
	'status_id'
  ];
  public function status(){
  	return $this->hasOne('App\Containers\Instagram\Models\InstaStatusModel', 'status_id', 'id');
  }
  public function comment(){
  	return $this->hasOne('App\Containers\Instagram\Models\InstaCommentsModel', 'comment_id', 'id');
  }
  public function insta_user(){
  	return $this->hasOne('App\Containers\Instagram\Models\InstaUserModel', 'user_id', 'userid_interaction');
  }
}
