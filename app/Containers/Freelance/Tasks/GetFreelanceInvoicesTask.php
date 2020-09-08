<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;



class GetFreelanceInvoicesTask extends Task
{
    public function run($req)
    {
      $email = $req->input('email');
      $freelance = FreelanceModel::where('email', $email)
                                  ->first();

      $invoices = ItinModel::where('satelite', $freelance->id_satellite)
                            ->simplePaginate(4)
                            ->load(['item' => function($q) {
                              $q->with('invoice');
                            }]);
	    return $invoices;
    }

}
