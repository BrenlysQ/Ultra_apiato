<?php

namespace  App\Containers\UltraSMS\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTypeModel extends Model
{
    protected $connection= 'dbmailer';
    protected $table='sms_type';
    protected $fillable=['id', 'id_campaign','receptores',
    'message','api'];
    

    public function type(){
        return $this->morphOne('App\Containers\UltraMailer\Models\CampaingsModel', 'type');
    }
}