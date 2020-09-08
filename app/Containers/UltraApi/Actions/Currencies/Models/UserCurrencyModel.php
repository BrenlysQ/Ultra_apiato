<?php
namespace App\Containers\UltraApi\Actions\Currencies\Models;

use Illuminate\Database\Eloquent\Model;

class UserCurrencyModel extends Model
{
    protected $table="api_users_currencies";
    protected $fillable=['idcurrency','iduser','created_at','updated_at'];
}
