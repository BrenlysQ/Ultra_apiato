<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaUserModel extends Model
{
  protected $table="insta_users";
  protected $primaryKey = 'user_id';
  protected $fillable=[
	'user_id',
	'user_name',
	'user_name_ig',
	'img_url'
  ];
}
