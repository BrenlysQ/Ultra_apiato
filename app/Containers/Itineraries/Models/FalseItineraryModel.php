<?php

namespace  App\Containers\Itinerary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;

class FalseItineraryModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance";
  protected $fillable=['id', 'base', 'fee_pu', 'fee_sat',
                      'loc', 'tax', 'tkt', 'total', 'st'];
  protected $dates = ['deleted_at'];

  public function item()
  {
      return $this->morphOne('App\Containers\Invoice\Models\ItemsModel', 'invoiceable');
  }

}
