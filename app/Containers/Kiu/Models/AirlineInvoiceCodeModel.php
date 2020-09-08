<?php

namespace  App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirlineInvoiceCodeModel extends Model
{
  use SoftDeletes;

  protected $table="airlines_invoice_code";
  protected $fillable=['id', 'airlines_code', 'invoice_code'];
  protected $dates = ['deleted_at'];

}
