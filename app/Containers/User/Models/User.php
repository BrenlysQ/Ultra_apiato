<?php

namespace App\Containers\User\Models;

use App\Containers\Stripe\Models\StripeAccount;
use App\Ship\Parents\Models\UserModel;

/**
 * Class User.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class User extends UserModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'device',
        'platform',
        'confirmed',
        'gender',
        'birth',
        'social_provider',
        'social_token',
        'social_refresh_token',
        'social_expires_in',
        'social_token_secret',
        'social_id',
        'social_avatar',
        'social_avatar_original',
        'social_nickname',
        'access_token',
    ];

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

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function currencies()
    {
        return $this->belongsToMany('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','api_users_currencies','iduser','idcurrency');
    }
    public function search_engines()
    {
        return $this->belongsToMany('App\Containers\Configuration\Models\API_search_engine','api_search_engine_user','iduser','idsearch_engine');
    }
    public function satellite()
    {
        return $this->hasOne('App\Containers\Satellite\Models\API_satellite','id','id');
    }

    public function stripeAccount()
    {
        return $this->hasOne(StripeAccount::class);
    }

    public function role()
    {
        return $this->hasOne('App\Containers\User\Models\Role','id','id');
    }

}
