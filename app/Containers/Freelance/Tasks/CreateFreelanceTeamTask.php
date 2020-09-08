<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceTeamModel;


class CreateFreelanceTeamTask extends Task
{
    public function run($req)
    {

      $name = $req->input('name');
      $limit = $req->input('limit');
      $satellite = $req->input('satellite');
      $freelance = new FreelanceTeamModel();
      $freelance->name = $name;
      $freelance->partners_limit = $limit;
      $freelance->satellite_owner = $satellite;
      $freelance->save();
      return $freelance;
    }

}
