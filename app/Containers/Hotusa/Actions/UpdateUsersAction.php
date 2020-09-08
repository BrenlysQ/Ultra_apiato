<?php
namespace App\Containers\Instagram\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use App\Containers\Instagram\Models\InstaUserModel;
use App\Containers\Instagram\Tasks\GetOrphanItems;
class UpdateUsersAction extends Action {
	public function run($ig){
		$orphans = $this->call(GetOrphanItems::class,[]);
		//$this->call(GetOrphanItems::class,[]);
		//$this->call(UpdateOrphans::class,[$orphans]);
		foreach($orphans as $orphan){
			$user_id = $orphan->from_user;
			if(!$ret = InstaUserModel::find($user_id)){
				$user_ig = $ig->people->getInfoById($user_id)->getUser();
				//print_r($user_ig->getUser()); die;
				$user = new InstaUserModel(); //$->getUsername();
				$user->user_id = $user_id;
				$user->user_name = '';
				$user->user_name_ig = $user_ig->username;
				$user->img_url = $user_ig->profile_pic_url;
				$user->save();
				sleep(1);
			}
		}
		print_r($orphans); die;
	}
	private function userExists($user_id){
		$ret = InstaUserModel::find($user_id);

	}
	private function cleanString($text) {
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}
}
