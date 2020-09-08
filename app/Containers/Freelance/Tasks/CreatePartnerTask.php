<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceTeamModel;


class CreatePartnerTask extends Task
{
    public function run($req)
    {

      $name = $req->input('name');
      $lastname = $req->input('lastname');
      $email = $req->input('email');
      $phone = $req->input('phone');
      $city = $req->input('city');
      $country = $req->input('country');
      $latitude = $req->input('latitude');
      $longitude = $req->input('longitude');
      $image = $req->input('image');
      $imagename = explode('@' , $email);
      $path = public_path('images/freelance/'.$imagename[0].'.jpg');
      Image::make($image)->encode('jpg')->save($path);
      $imagetosave = $imagename[0].'.jpg';
      $facebook = $req->input('facebook');
      $twitter = $req->input('twitter');
      $instagram = $req->input('instagram');
      $gender = $req->input('gender');
      $website = $req->input('website');
      $team_email = $req->input('team_email');
      $owner = FreelanceModel::where('email', $team_email)->with('team')->first();
      $team = FreelanceTeamModel::where('id', $owner->team)->with('freelances')->first();
      if ($team->partners_limit >= count($team->freelances)) {
        return json_encode($response = array('error' => 'error', ));
      }else {
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
        $freelance->team = $team;
        $freelance->id_satellite = $owner->id_satellite;
        $freelance->save();
      }
      return $freelance;
    }

}
