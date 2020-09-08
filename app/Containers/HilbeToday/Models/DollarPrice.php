<?php

namespace App\Containers\HilbeToday\Models;

use Illuminate\Database\Eloquent\Model;

class DollarPrice extends Model
{
    protected $table="ht_dollarprice";
	protected $fillable=[
	'hilbe_price','timestamp', 'dt_price', 'percentage', 'ht_config_percentage_bsf',
	];

	public function config(){
		return belongsTo('app\HilbeToday\Models\HtConfig');
	}
}
