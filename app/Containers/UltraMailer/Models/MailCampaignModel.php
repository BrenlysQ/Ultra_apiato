<?php

namespace  App\Containers\UltraMailer\Models;

use Illuminate\Database\Eloquent\Model;

class MailCampaignModel extends Model
{
    protected $connection= 'dbmailer';
    protected $table='mail_campaign';
    protected $fillable=['id', 'id_campaign','receptores',
    'message','header_mail','subject'];
    

    public function config(){
        return $this->hasOne('App\Containers\UltraMailer\Models\ConfigHeaderModel', 'id', 'header_mail');
    }
    public function type(){
        return $this->morphOne('App\Containers\UltraMailer\Models\CampaingsModel', 'type');
    }
}
