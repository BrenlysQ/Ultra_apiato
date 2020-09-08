<?php

namespace App\Containers\UltraApi\Models;

use App\Ship\Parents\Models\Model;

class GdsTagsId extends Model
{
  protected $table = "gds_tags_id";
  protected $fillable = ['type','tag_id','tag_pu','gen_date'];
}
