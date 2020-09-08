<?php
namespace App\Containers\Configuration\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Configuration\Models\API_search_engine;
use App\Ship\Exceptions\UpdateResourceFailedException;

class SearchEngineHandler{

  public static function createSearchEngine(){
    $name = Input::get('name');
    $data = (object) array(
      "name" => $name 
    );
    $search_engine = new API_search_engine();
    $search_engine->name = $data->name;
    $search_engine->save();
  }

  public static function listSearchEngines(){
    return API_search_engine::get()->toJson();
  }

  public static function getSearchEngine($id){
    return API_search_engine::where('id',$id)->first()->toJson();
  }
  
  public static function deleteSearchEngine($id = false){
    if(!$id)
      $id = Input::get('id'); //IF NOT ID BY PARAMETER, LOOK FOR REQUEST DATA
    $search_engine = API_search_engine::find($id);
    if ($search_engine->trashed()){ //IF THE MODEL IS TRASEHD< SO DELETE IT FOREVER
      $search_engine->forceDelete();
    }else{ //ELSE PLEASE SOFTDELETE IT :-)
      $search_engine->delete();
    }
  }

  public static function updatingSearchEngine(){
    $id = Input::get('id',false);
    $name = Input::get('name',false);
    $search_engine = API_search_engine::findOrFail($id);
    $search_engine->name = $name;
    $search_engine->update();
  }

}

