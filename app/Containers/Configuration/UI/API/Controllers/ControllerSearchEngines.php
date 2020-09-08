<?php

namespace App\Containers\Configuration\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Configuration\Actions\SearchEngineHandler;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Containers\User\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Api\Caller\ApiCall;



class ControllerSearchEngines extends ApiController
{

    public function StoreSearchEngine(){
    	return SearchEngineHandler::createSearchEngine();
    }

    public function GetSearchEngineList(){
    	return SearchEngineHandler::listSearchEngines(); 
	} 

	public function bundledSearch_engine(Request $request){
        $search_engineid = $request->input('id',false);
        $search_engine = SearchEngineHandler::getSearchEngine($search_engineid);
        $response = '{ "search_engines" : ' . $search_engine . '}';
        return $response;
    }

    public function updateSearch_engine(){
        return SearchEngineHandler::updatingSearchEngine();
    }

    public function deleteSearch_engine(){
      return SearchEngineHandler::deleteSearchEngine();
    }
  
}
