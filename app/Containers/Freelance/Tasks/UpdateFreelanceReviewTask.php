<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceReviewsModel;


class UpdateFreelanceReviewTask extends Task
{
  public function run($req){
    $comment = $req->input('comment');
    $ranking = $req->input('user_ranking');
    $invoice = $req->input('invoice');

    $review = FreelanceReviewsModel::where('id_invoice', $invoice)->first();
    $review->comment = $comment;
    $review->user_ranking = $ranking;
    $review->update();
    
    return $review;
  }

}
