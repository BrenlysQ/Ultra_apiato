<?php

namespace App\Containers\Hotusa\Models;

use Illuminate\Database\Eloquent\Model;
class HotusaBooking extends Model
{
  protected $table="hotusa_booking";
  protected $fillable=[
    'booking_id','short_booking_id','total_amount','currency',
	'file_number', 'pre_book', 'confirm_book','booking_bonus','option','pax_responsible'
  ];
  public function item()
  {
      return $this->morphOne('App\Containers\Invoice\Models\ItemsModel', 'invoiceable');
  }

  public function getPreBookAttribute($value)
  {
  	return json_decode($value);
  }
  public function getOptionAttribute($value)
  {
  	return json_decode($value);
  }
  public function getPaxResponsibleAttribute($value)
  {
  	return json_decode($value);
  }
  public function getConfirmBookAttribute($value)
  {
  	return json_decode($value);
  }
  public function getBookingBonusAttribute($value)
  {
    return json_decode($value);
  }
}
