<?php

namespace App\Containers\Instagram\Models;
use Illuminate\Database\Eloquent\Model;

class InstaContactsModel extends Model
{
  protected $table="insta_contacts";
  protected $fillable=[
    'title','name','email','phone','address'
  ];
}
