<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceBankInfoModel;



class CreateFreelanceBankInfoTask extends Task
{
    public function run($req)
    {
      $id = $req->input('id');
      $identification = $req->input('identification');
      $routing = $req->input('routing_number');
      $account = $req->input('account_number');

      $bank = new FreelanceBankInfoModel();
      $bank->id_freelance = $id;
      $bank->identification = $identification;
      $bank->routing_number = $routing;
      $bank->account_number = $account;
      $bank->save();

	    return $bank;
    }

}
