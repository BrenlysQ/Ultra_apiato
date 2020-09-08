<?php

namespace App\Containers\HilbeToday\Models;

use Illuminate\Database\Eloquent\Model;

class HtConfig extends Model
{
    protected $table="ht_config";
	protected $fillable=[
	'percentage_hilbe','emails','percentage_bsf','percentage_notice'
	];

	public function dollarprice(){
		return $this->hasOne('app\HilbeToday\Models\DollarPrice','');
	}
}
