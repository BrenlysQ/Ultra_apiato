<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Intervention\Image\ImageManagerStatic as Image;
use App\Containers\UltraApi\Tasks\SatelliteEmailCurlTask;
use App\Containers\Satellite\Models\API_satellite;



class CreateFreelanceTask extends Task
{
  public function run($req){
    $satellite = API_satellite::where('domain', 'https://www.plusultrapagos.com')->first();
    $name = $req->input('name');
    $lastname = $req->input('lastname');
    $email = $req->input('email');
    $phone = $req->input('phone');
    $city = $req->input('city');
    $country = $req->input('country');
    $latitude = $req->input('latitude');
    $longitude = $req->input('longitude');
    $image = $req->input('image');
	if($image == '/img/photo-default.png'){
		$imagetosave = 'info.jpg';
	}else{
		$imagename = explode('@' , $email);
		$path = public_path('images/freelance/'.$imagename[0].'.jpg');
		Image::make($image)->encode('jpg')->save($path);
		$imagetosave = $imagename[0].'.jpg';
	}
    $idimage = $req->input('idimage');
    $imagename = explode('@' , $email);
    $path = public_path('images/freelance/'.$imagename[0].'id.jpg');
    Image::make($image)->encode('jpg')->save($path);
    $idimagetosave = $imagename[0].'id.jpg';

    $languages = $req->input('languages');
    $exp_years = $req->input('exp_years');
    $ranking = $req->input('ranking');
    $sales = $req->input('sales');
    $skills = $req->input('skills');
    $facebook = $req->input('facebook');
    $twitter = $req->input('twitter');
    $instagram = $req->input('instagram');
    $gender = $req->input('gender');
    $website = $req->input('website');
    $places = $req->input('places');
    $plusrank = $req->input('ranking_plus');
    $team = $req->input('team');
    $satellite = $req->input('satellite');
    $identification = $req->input('identification');
    $routing = $req->input('routing_number');
    $account = $req->input('account_number');
	$places = $req->input('places');
	$code = $req->input('code');
	$accountid = $req->input('accountid');
	$bankname = $req->input('bankname');
	$accounttype = $req->input('accounttype');
	$accountowner = $req->input('accountowner');

    $freelance = new FreelanceModel();
    $freelance->name = $name;
    $freelance->lastname = $lastname;
    $freelance->email = $email;
    $freelance->phone = $phone;
    $freelance->city = $city;
    $freelance->country = $country;
    $freelance->latitude = $latitude;
    $freelance->longitude = $longitude;
    $freelance->image = $imagetosave;
    $freelance->facebook = $facebook;
    $freelance->instagram = $instagram;
    $freelance->twitter = $twitter;
    $freelance->gender = $gender;
    $freelance->website = $website;
    $freelance->id_satellite = $satellite;
    $freelance->team = $team;
    $freelance->save();

    $freelance->features()->create([
      'exp_years' => $exp_years,
      'completed_sales' => $sales,
      'languages' => $languages,
      'skills' => $skills,
      'ranking' => $ranking,
      'common_places' => $places,
      'ranking_plus' => $plusrank
      ]);
      $freelance->bankinfo()->create([
        'id_freelance' => $freelance->id,
        'identification' => $accountid,
        'routing_number' => $routing,
        'account_number' => $account,
		'bank_name' => $bankname,
		'account_type' => $accounttype,
		'account_owner' => $accountowner
      ]);
	  $freelance->confirmcode()->create([
        'id_freelance' => $freelance->id,
        'code' => $code
    ]);
    $freelance->identification()->create([
        'id_freelance' => $freelance->id,
        'identification' => $identification,
        'image' => $idimagetosave
    ]);
    //$response = (new SatelliteEmailCurlTask())->run($satellite,$freelance,'add_freelance');
    return $freelance;
  }

}
