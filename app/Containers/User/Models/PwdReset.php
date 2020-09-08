<?php

namespace App\Containers\User\Models;
use App\Ship\Parents\Models\Model;
/**
 * Class User.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class PwdReset extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'code',
    ];

    /**
     * The dates attributes.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];
    public function setUpdatedAtAttribute($value)
    {
        // to Disable updated_at
    }
}
