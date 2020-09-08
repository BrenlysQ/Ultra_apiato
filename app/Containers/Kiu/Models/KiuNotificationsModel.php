<?php

namespace  App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Firebase\SyncsWithFirebase;

class KiuNotificationsModel extends Model
{
	use SoftDeletes;
	use SyncsWithFirebase;
	protected $connection=  'kiu_fares';
	protected $table= "kiu_notifications";
	protected $fillable= ['footprint', 'data'];
	protected $dates = ['deleted_at'];

	protected function getFirebaseSyncData()
	{
		if ($fresh = $this->fresh()) {
			//$fresh->data = '{"processes":[{"proccess_id":"21454","route":"1","command":"php kiu:fares 1","footprint":"CCS\/MIA","date_start":"2018-09-10","reverse":false,"oneway":false},{"proccess_id":"21458","route":"1","command":"php kiu:fares 1","footprint":"MIA\/CCS","date_start":"2018-09-10","reverse":true,"oneway":false},{"proccess_id":"21460","route":"1","command":"php kiu:fares 1","footprint":"MIA\/CCS","date_start":"2018-09-10","reverse":true,"oneway":true}]}';
			$obj = json_decode($fresh->data);
			if(is_object($obj)){
				$fresh->data = $obj;
			}
			return $fresh->toArray();
		}
	}
}
