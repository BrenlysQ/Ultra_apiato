<?php

namespace App\Containers\Insurance\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Insurance\Actions\InsuranceHandler;
use Illuminate\Http\Request;

class ControllerInsurance extends ApiController
{

  public function GetPlans(){
    return InsuranceHandler::GetPlans();
  }

  public function GenerateCot(){
    return InsuranceHandler::GenerateCot();
  }

  public function GetQuotation(){
    return InsuranceHandler::GetQuotation();
  }
  public function BookInsurance(){
	$ret = InsuranceHandler::BookInsurance();
	return response()->json($ret);
  }
  public function ViewCoverage(){
    return InsuranceHandler::ViewCoverage();
  }
  public function getInsuCharts(){
    return InsuranceHandler::getInsuCharts();
  }
  public function getInsuranceReport(Request $req){
    return InsuranceHandler::getInsuranceReport($req);
  }
}
