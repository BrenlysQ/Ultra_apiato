<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceReviewsModel;



class GetFreelanceReviewsTask extends Task
{
    public function run($req)
    {
      $id = $req->input('id');
      $review = FreelanceReviewsModel::where('id_invoice',$id)
                    ->first();
      return $review;

    }

}
