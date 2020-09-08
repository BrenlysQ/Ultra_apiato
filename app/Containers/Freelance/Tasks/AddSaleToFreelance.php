<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceReviewsModel;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\UltraApi\Tasks\HandlerFreelanceBook;

class AddSaleToFreelance extends Task
{
  public function run($id){
    $features = FreelanceFeaturesModel::where('id_freelance', $id)->first();
    $features->completed_sales += 1;
    return $features;
  }

}
