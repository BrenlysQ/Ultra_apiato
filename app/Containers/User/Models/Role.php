<?php

namespace App\Containers\User\Models;
use App\Ship\Parents\Models\Model;


/**
 * Class User.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class Role extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name','description'];

    /**
     * The dates attributes.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

 
    public function user(){
        return $this->belongsTo('App\Containers\User\Models\Role', 'id', 'id');
    }

    public function RolesHasMenu(){
        return $this->hasMany('App\Containers\User\Models\MenuAdmin', 'id_rol', 'id_menu');
    } 
}

