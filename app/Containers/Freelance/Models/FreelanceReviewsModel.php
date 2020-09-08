<?php

namespace  App\Containers\Freelance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreelanceReviewsModel extends Model
{
  use SoftDeletes;

  protected $table="puf_freelance_reviews";
  protected $fillable=['id', 'id_freelance', 'comment',
          'user_ranking', 'id_invoice', 'satellite_id'];
  protected $dates = ['deleted_at'];

  public function freelance(){
    return $this->belongsTo('App\Containers\Freelance\Models\FreelanceModel', 'id_freelance' , 'id');
  }
  public function invoice(){
    return $this->belongsTo('App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel','id_invoice','id');
  }
  public function satellite(){
	  return $this->belongsTo('App\Containers\Satellite\Models\API_satellite','satellite_id','id');
  }
}
