<?php

namespace App\Containers\Freelance\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Freelance\Actions\FreelanceHandler;
use Illuminate\Http\Request;

class ControllerFreelance extends ApiController
{
  public $freelance;
  public function __construct(){
    $this->freelance = new FreelanceHandler();
  }
  public function addFreelance(Request $req){
    return $this->freelance->create($req);
  }
  public function updateFreelance(Request $req){
    return $this->freelance->update($req);
  }
  public function deleteFreelance(){
    return $this->freelance->delete();
  }
  public function getFreelance(){
    return $this->freelance->get();
  }
  public function editFreelance(){
    return $this->freelance->edit();
  }
  public function nearestFreelance(Request $req){
    return $this->freelance->nearest($req);
  }
  public function getReviews(Request $req){
    return $this->freelance->reviews($req);
  }
  public function addReview(Request $req){
    return $this->freelance->addReview($req);
  }
  public function updateReview(Request $req){
    return $this->freelance->updateReview($req);
  }
  public function getInvoices(Request $req){
    return $this->freelance->getInvoices($req);
  }
  public function reviewReminder(){
    return $this->freelance->reviewReminder();
  }
  public function getSellsCount(Request $req){
	   return response()->json($this->freelance->getSellsCount($req));
  }
  public function addCampaign(Request $req){
    return $this->freelance->addCampaign($req);
  }
  public function updateCampaign(Request $req){
    return $this->freelance->updateCampaign($req);
  }
  public function editCampaign(Request $req){
    return $this->freelance->editCampaign($req);
  }
  public function deleteCampaign(){
    return $this->freelance->deleteCampaign();
  }
  public function getFees(Request $req){
    return response()->json($this->freelance->getFees($req));
  }
  public function getBalance(Request $req){
    return response()->json($this->freelance->getBalance($req));
  }
  public function getBankInfo(Request $req){
    return $this->freelance->getBankInfo($req);
  }
  public function createBankInfo(Request $req){
    return $this->freelance->createBankInfo($req);
  }
  public function updateBankInfo(Request $req){
    return $this->freelance->updateBankInfo($req);
  }
  public function createPartner(Request $req){
    return $this->freelance->createPartner($req);
  }
  public function createTeam(Request $req){
    return $this->freelance->createTeam($req);
  }
  public function getPartnerChart(Request $req){
    return $this->freelance->getPartnerChart($req);
  }
  public function comparePartners(Request $req){
    return $this->freelance->comparePartners($req);
  }
  public function getCredit(Request $req){
    return $this->freelance->getCredit($req);
  }
  public function getDebt(Request $req){
    return $this->freelance->getDebt($req);
  }
  public function getCreditInvoices(Request $req){
    return $this->freelance->getCreditInvoices($req);
  }
  public function testTask(Request $req){
    return $this->freelance->testTask($req);
  }
  public function getFreelancePartner(Request $req){
    return $this->freelance->getPartners($req);
  }
  public function searchFreelances(Request $req){
    return $this->freelance->searchFreelances($req);
  }
  public function getRankedFreelances(){
    return $this->freelance->getRankedFreelances();
  }
  public function getFreelancesZone(Request $req = null){
    return $this->freelance->getFreelancesZone($req);
}
   public function updateFreelanceStatus(){
      return $this->freelance->updateFreelanceStatus();
  }
   public function getFreelanceCountries(){
      return $this->freelance->getFreelanceCountries();
  }
   public function getInvoiceReview(Request $req){
      return $this->freelance->getInvoiceReview($req);
  }
}
