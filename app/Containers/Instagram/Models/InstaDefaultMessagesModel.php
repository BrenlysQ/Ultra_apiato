<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaDefaultMessagesModel extends Model
{
  protected $table="insta_default_messages";
  protected $fillable=[
    'title','message'
  ];
}
