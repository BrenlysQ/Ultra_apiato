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


class UpdateFreelanceTask extends Task
{
    public function run($req)
    {
      $id = $req->input('id');
      $name = $req->input('name');
      $lastname = $req->input('lastname');
      $email = $req->input('email');
      $phone = $req->input('phone');
      $city = $req->input('city');
      $country = $req->input('country');
      $latitude = $req->input('latitude');
      $longitude = $req->input('longitude');
      $hasfile = $req->input('hasfile');
      $languages = $req->input('languages');
      $exp_years = $req->input('exp_years');
      $ranking = $req->input('ranking');
      $sales = $req->input('sales');
      $skills = $req->input('skills');
      $feature_id = $req->input('feature_id');
      $gender = $req->input('gender');
      $website = $req->input('website');
      $facebook = $req->input('facebook');
      $twitter = $req->input('twitter');
      $instagram = $req->input('instagram');
      $plusrank = $req->input('ranking_plus');
  	  $places = $req->input('places');
  	  $identification = $req->input('identification');
  	  $routing = $req->input('routing_number');
  	  $account = $req->input('account_number');

      if ($hasfile == 0) {
        $imagetosave = $req->input('image');
      }else {
        $image = $req->input('image');
        $imagename = explode('@' , $email);
        $path = public_path('images/freelance/'.$imagename[0].'.jpg');
        $imageparsed = Image::make($image)->encode('jpg')->save($path);
        $imagetosave = $imagename[0].'.jpg';
      }

      $freelance = FreelanceModel::findOrFail($id);
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
      $freelance->update();

      $freelance->features()->update([
        'exp_years' => $exp_years,
        'completed_sales' => $sales,
        'languages' => $languages,
        'skills' => $skills,
        'ranking' => $ranking,
        'common_places' => $places,
        'ranking_plus' => $plusrank,
      ]);

	  $freelance->bankinfo()->update([
		'identification' => $identification,
		'routing_number' => $routing,
		'account_number' => $account,
	  ]);
	    return $freelance;
    }
}
