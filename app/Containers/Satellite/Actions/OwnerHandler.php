<?php
namespace App\Containers\Satellite\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Satellite\Models\API_owner;

class OwnerHandler{

  public static function createOwner($data){
    $owner = new API_owner();
    
    $owner->name = $data->name;
    $owner->identification = $data->identification;
    $owner->email = $data->email;
    $owner->address = $data->address;
    $owner->telephone = $data->telephone;
    $owner->save();

    return $owner->id;

  }

  public static function updateOwner($data,$id){
    $owner = API_owner::find($id);
    $owner->name = $data->name;
    $owner->identification = $data->identification;
    $owner->email = $data->email;
    $owner->address = $data->address;
    $owner->telephone = $data->telephone;
    $owner->save();
  }

}

