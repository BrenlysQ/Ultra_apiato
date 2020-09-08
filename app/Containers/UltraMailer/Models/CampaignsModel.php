<?php

namespace  App\Containers\UltraMailer\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsModel extends Model
{
    protected $connection= 'dbmailer';
    protected $table='campaigns';
    protected $fillable=['id', 'title', 'type','sent_by','date_end','status'];
    

    public function status(){
        return $this->hasOne(' App\Containers\UltraMailer\Models\CampaingStatusModel', 'id', 'status');
    }
    public function type(){
        return $this->morphTo();
    }
}