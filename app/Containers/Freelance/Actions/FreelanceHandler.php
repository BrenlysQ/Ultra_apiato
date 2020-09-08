<?php

namespace App\Containers\Freelance\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Tasks\CreateFreelanceTask;
use App\Containers\Freelance\Tasks\UpdateFreelanceTask;
use App\Containers\Freelance\Tasks\DeleteFreelanceTask;
use App\Containers\Freelance\Tasks\GetFreelanceTask;
use App\Containers\Freelance\Tasks\EditFreelanceTask;
use App\Containers\Freelance\Tasks\GetNearestFreelanceTask;
use App\Containers\Freelance\Tasks\GetFreelanceReviewsTask;
use App\Containers\Freelance\Tasks\AddFreelanceReviewTask;
use App\Containers\Freelance\Tasks\UpdateFreelanceReviewTask;
use App\Containers\Freelance\Tasks\GetFreelanceInvoicesTask;
use App\Containers\Freelance\Tasks\ReviewReminderTask;
use App\Containers\Freelance\Tasks\GetSellsCountTask;
use App\Containers\Freelance\Tasks\GetFeesTask;
use App\Containers\Freelance\Tasks\GetBalanceTask;
use App\Containers\Freelance\Tasks\AddFreelanceCampaignsTask;
use App\Containers\Freelance\Tasks\UpdateFreelanceCampaignsTask;
use App\Containers\Freelance\Tasks\EditFreelanceCampaignsTask;
use App\Containers\Freelance\Tasks\DeleteFreelanceCampaignsTask;
use App\Containers\Freelance\Tasks\GetFreelanceBankInfoTask;
use App\Containers\Freelance\Tasks\CreateFreelanceBankInfoTask;
use App\Containers\Freelance\Tasks\UpdateFreelanceBankInfoTask;
use App\Containers\Freelance\Tasks\CreateFreelanceTeamTask;
use App\Containers\Freelance\Tasks\CreatePartnerTask;
use App\Containers\Freelance\Tasks\GetPartnerChartTask;
use App\Containers\Freelance\Tasks\ComparePartnersTask;
use App\Containers\Freelance\Tasks\GetCreditTask;
use App\Containers\Freelance\Tasks\GetDebtTask;
use App\Containers\Freelance\Tasks\GetCreditInvoicesTask;
use App\Containers\Freelance\Tasks\TestTask;
use App\Containers\Freelance\Tasks\GetFreelancePartnersTask;
use App\Containers\Freelance\Tasks\GetRankedFreelancesTask;
use App\Containers\Freelance\Tasks\SearchFreelancesTask;
use App\Containers\Freelance\Tasks\GetFreelancesZoneTask;
use App\Containers\Freelance\Tasks\UpdateFreelanceStatusTask;
use App\Containers\Freelance\Tasks\GetFreelanceCountriesTask;


class FreelanceHandler extends Action {
      public function create($req){
        $response = $this->call(CreateFreelanceTask::class,[$req]);
        if($response){
          return true;
        }else {
          return 'Error al registrar';
        }
      }

    public function update($req){
      $response = $this->call(UpdateFreelanceTask::class,[$req]);
      return $response;
    }

    public function delete(){
      $response = $this->call(DeleteFreelanceTask::class,[]);
      return $response;
    }

    public function get(){
      $response = $this->call(GetFreelanceTask::class,[]);
      return $response;
    }

    public function edit(){
      $response = $this->call(EditFreelanceTask::class,[]);
      return $response;
    }

    public function nearest($req){
      $response = $this->call(GetNearestFreelanceTask::class,[$req]);
      return $response;
    }

    public function reviews($req){
      $response = $this->call(GetFreelanceReviewsTask::class,[$req]);
      return $response;
    }

    public function addReview($req){
      $response = $this->call(AddFreelanceReviewTask::class,[$req]);
      return $response;
    }

    public function updateReview($req){
      $response = $this->call(UpdateFreelanceReviewTask::class,[$req]);
      return $response;
    }

    public function getInvoices($req){
      $response = $this->call(GetFreelanceInvoicesTask::class,[$req]);
      return $response;
    }

    public function reviewReminder(){
      $response = $this->call(ReviewReminderTask::class,[]);
      return $response;
    }

    public function getSellsCount($req){
      $response = $this->call(GetSellsCountTask::class,[$req]);
	     return $response;
    }

    public function addCampaign($req){
      $response = $this->call(AddFreelanceCampaignsTask::class,[$req]);
      return $response;
    }

    public function updateCampaign($req){
      $response = $this->call(UpdateFreelanceCampaignsTask::class,[$req]);
      return $response;
    }

    public function editCampaign($req){
      $response = $this->call(EditFreelanceCampaignsTask::class,[$req]);
      return $response;
    }

    public function deleteCampaign(){
      $response = $this->call(DeleteFreelanceCampaignsTask::class,[]);
      return $response;
    }

    public function getFees($req){
      $response = $this->call(GetFeesTask::class,[$req]);
      return $response;
    }

    public function getBalance($req){
      $response = $this->call(GetBalanceTask::class,[$req]);
      return $response;
    }

    public function getBankInfo($req){
      $response = $this->call(GetFreelanceBankInfoTask::class,[$req]);
      return $response;
    }

    public function updateBankInfo($req){
      $response = $this->call(UpdateFreelanceBankInfoTask::class,[$req]);
      return $response;
    }

    public function createPartner($req){
      $response = $this->call(CreatePartnerTask::class,[$req]);
      return $response;
    }

    public function createTeam($req){
      $response = $this->call(CreateFreelanceTeamTask::class,[$req]);
      return $response;
    }

    public function getPartnerChart($req){
      $response = $this->call(GetPartnerChartTask::class,[$req]);
      return $response;
    }

    public function comparePartners($req){
      $response = $this->call(ComparePartnersTask::class,[$req]);
      return $response;
    }
    public function getCredit($req){
      // print_r($req); die;
      $response = $this->call(GetCreditTask::class,[$req]);
      return $response;
    }
	public function getDebt($req){
      // print_r($req); die;
      $response = $this->call(GetDebtTask::class,[$req]);
      return $response;
    }
	public function getCreditInvoices($req){
      // print_r($req); die;
      $response = $this->call(GetCreditInvoicesTask::class,[$req]);
      return $response;
    }
	public function testTask($req){
      // print_r($req); die;
      $response = $this->call(testTask::class,[$req]);
      return $response;
    }
    public function getPartners($req){
      $response = $this->call(GetFreelancePartnersTask::class,[$req]);
      return $response;
    }
    public function searchFreelances($req){
      $response = $this->call(SearchFreelancesTask::class,[$req]);
      return $response;
    }
    public function getRankedFreelances(){
      $response = $this->call(GetRankedFreelancesTask::class,[]);
      return $response;
    }
	  public function getFreelancesZone($req){
      $response = $this->call(GetFreelancesZoneTask::class,[$req]);
      return $response;
    }	
	  public function updateFreelanceStatus(){
      $response = $this->call(UpdateFreelanceStatusTask::class,[]);
      return $response;
    }
	  public function getFreelanceCountries(){
      $response = $this->call(GetFreelanceCountriesTask::class,[]);
      return $response;
    }
	  public function getInvoiceReview($req){
      $response = $this->call(GetInvoiceReviewTask::class,[$req]);
      return $response;
    }
}
