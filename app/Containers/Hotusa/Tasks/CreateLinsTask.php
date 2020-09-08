<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;


class CreateLinsTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($items,$seqnumbers = false)
    {
		if(!$seqnumbers){
			$seqnumbers = Input::get('seqnumbers');
		}
		//print_r($items); die;
		$lins = array();
		foreach($items as $key => $item){
			$reg_index = $seqnumbers[$key][2];
			$hab = $item->rooms[$seqnumbers[$key][1]];
			if(is_array($hab->reg)){
				$lin = $hab->reg[$reg_index]->lin;
			}else{
				$lin = $hab->reg->lin;
			}
			if(!is_array($lin)){
				$oldlin = $lin;
				$lin = array();
				$lin[] = $oldlin;
			}

			$lins = array_merge($lins, $lin);
			//$lins .= $item->rooms[$seqnumbers[$key][1]];
			//print_r($item->rooms[$seqnumbers[$key][1]]);
		}
		return $lins;
    }

}
