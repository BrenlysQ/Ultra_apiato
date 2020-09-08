<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaContactsModel;

class GetListContactTask extends Task
{

    /**
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run()
    {
    	return InstaContactsModel::all();
   	}
}
