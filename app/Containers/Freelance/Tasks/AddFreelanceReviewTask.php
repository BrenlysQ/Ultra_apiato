<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceReviewsModel;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\UltraApi\Tasks\HandlerFreelanceBook;

class AddFreelanceReviewTask extends Task
{
  public function run($req){
    $satellite = $req->input('id_freelance');
    $freelance = FreelanceModel::where('id_satellite', $satellite)->first();
    $id_freelance = $freelance->id;
    $comment = $req->input('comment');
    $ranking = $req->input('user_ranking');
    $invoice = $req->input('invoice');
    $review = new FreelanceReviewsModel();
    $review->id_freelance = $id_freelance;
    $review->comment = $comment;
    $review->user_ranking = $ranking;
    $review->id_invoice = $invoice;
    $review->save();
    $free = (new HandlerFreelanceBook())->run($satellite);
    return $review;
  }

}
