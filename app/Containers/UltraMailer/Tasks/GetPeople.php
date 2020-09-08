<?php

namespace App\Containers\UltraMailer\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
// use App\Containers\UltraMailer\Models\MailCampaignModel;
use App\Containers\UltraMailer\Models\UltraMailer;



class GetPeople extends Task
{
    public function run($city)
    {
       // $mailcampaign = (new FullMailCampaign())->run();
       // $req = MailCampaignModel::select('receptores')->where('receptores',$city)->get();
    // $requ = MailCampaignModel::get([ 'receptores']);

     // dd(json_decode($requ));
    // dd($city);
     // $query=  UltraMailer:: get(['email','ciudad']);
      // $req = UltraMailer:: get(['id','email'])->where('receptores',$requ);

    $query=  UltraMailer:: select('email')->where('ciudad',$city)->get(); //seleccionar todos los emails de esa ciudad en la tabla aliados 
     dd(json_decode($query));


    	//consulta a la bd el campo ciudad   	
    	// foreach ($cities as $city){
    		// $receptores[] = $req->email;
    		// aliados::where('ciudad', $request->ciudad)
    		// json_encode($receptores);
    	// }
    
		//$receptores = Input::get('receptores');//recibir un json
		//$receptores->receptores = $receptores;
		

		//dd(json_decode($tablecampaign->status));
		// $receptores->save();
	  	// return $receptores;
	}
}