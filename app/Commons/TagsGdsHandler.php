<?php
namespace App\Commons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Commons\Models\GdsTagsModel;
class TagsGdsHandler{
  public static function StoreTag($tagid,$payload){
    $tagpu = str_random(10);
    $gdstag = new GdsTagsModel();
    $gdstag->type = $payload->se;
    $gdstag->tag_id = $tagid;
    $gdstag->tag_pu = $tagpu;
    $gdstag->itinerary = json_encode($payload);
    $gdstag->gen_date = date('Y-m-d H:i:s');
    $gdstag->save();
    return $tagpu;
  }
  public static function GetGdsTag($tagpu = false){
    if(!$tagpu){
      $tagpu = Input::get('tagpu');
    }
  //print_r($tagpu); die;
  $taggds = DB::table('gds_tags_id')->select('tag_id','itinerary','gen_date')->where('tag_pu',$tagpu)->first();
    if($taggds){
      //print_r($taggds);
	  return $taggds;
    }else{
		//print_r('MARDICON');
		return false;
	}

  }

  public static function StoreCarTag($tagid,$payload){
    //print_r($payload); die;
    $tagpu = str_random(10);
    $gdstag = new GdsTagsModel();
    $gdstag->type = $payload->se;
    $gdstag->tag_id = $tagid;
    $gdstag->tag_pu = $tagpu;
    $gdstag->itinerary = json_encode($payload);
    $gdstag->gen_date = date('Y-m-d H:i:s');
    $gdstag->save();
    return $tagpu;
  }
  
  
  public static function DestroyGdsTag($tagpu){
    GdsTagsModel::where('tag_pu',$tagpu)->delete();
  }

}
?>
