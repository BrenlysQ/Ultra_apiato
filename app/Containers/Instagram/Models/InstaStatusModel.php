<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaStatusModel extends Model
{
  protected $table="insta_status";
  protected $fillable=[
	'title'
  ];
}
