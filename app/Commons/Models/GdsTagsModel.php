<?php

namespace App\Commons\Models;

use Illuminate\Database\Eloquent\Model;

class GdsTagsModel extends Model
{
  protected $table="gds_tags_id";
  protected $fillable=['type','tag_id','tag_pu','itinerary'];
  public function getItineraryAttribute($value){
    return json_decode($value);
  }
}
