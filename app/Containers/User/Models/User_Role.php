<?php

namespace App\Containers\User\Models;
use App\Ship\Parents\Models\Model;

class User_Role extends Model
{
    protected $table='user_has_roles';
    protected $fillable=['role_id','user_id','created_at','updated_at'];
}
