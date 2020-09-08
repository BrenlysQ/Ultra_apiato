<?php

namespace  App\Containers\UltraMailer\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignStatusModel extends Model
{
    protected $connection= 'dbmailer';
    protected $table='campaign_status';
    protected $fillable=['id', 'name'];
    

    public function freelance(){
        return $this->belongsTo('App\Containers\UltraMailer\Models\CampaingsModel', 'status', 'id');
    }
}
