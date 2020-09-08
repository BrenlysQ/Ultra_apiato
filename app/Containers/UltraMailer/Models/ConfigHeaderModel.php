<?php

namespace  App\Containers\UltraMailer\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigHeaderModel extends Model
{
    protected $connection= 'dbmailer';
    protected $table='config_header';
    protected $fillable=['id', 'title', 'from','config_json','reply_to','name'];
    

    public function freelance(){
        return $this->belongsTo('App\Containers\UltraMailer\Models\MailCampaignModel', 'header_mail', 'id');
    }
}