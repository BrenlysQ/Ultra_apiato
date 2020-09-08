<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Intervention\Image\ImageManagerStatic as Image;


class HandlerFreelanceBook extends Task
{
    public function run($id)
    {
      $id = 84;
      $sum = 0;
      $freelance = FreelanceModel::where('id_satellite',$id)
                    ->with('features','reviews')
                    ->first();
      $sales = json_decode($freelance);

      foreach ($freelance->reviews as $review) {
        $sum += $review->user_ranking;
      }
      $cant = count($freelance->reviews);
      $user_ranking = ($sum/$cant) * 0.7;
      $ranking_plus = $freelance->ranking_plus * 0.3;
      $ranking = round($user_ranking + $ranking_plus,2);
      $freelance->features()->update([
        'completed_sales' => ($sales->features->completed_sales + 1),
        'ranking' => $ranking,
      ]);
      return $freelance;
    }
}
