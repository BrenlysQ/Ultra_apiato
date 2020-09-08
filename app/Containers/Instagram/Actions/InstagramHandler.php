<?php
namespace App\Containers\Instagram\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Instagram\Tasks\ManageStoriesTask;
use App\Containers\Instagram\Tasks\LoginInstagramTask;
use App\Containers\Instagram\Tasks\RecentActivityTask;
use App\Containers\Instagram\Tasks\GetMessagesTask;
use App\Containers\Instagram\Tasks\ManageMessagesTask;
use App\Containers\Instagram\Tasks\ListLikesTask;
use App\Containers\Instagram\Tasks\ListCommentsTask;
use App\Containers\Instagram\Tasks\ListMessagesTask;
use App\Containers\Instagram\Tasks\SendMessageTask;
use App\Containers\Instagram\Tasks\GetDefMessagesTask;
use App\Containers\Instagram\Tasks\UpdateStatusTask;
use App\Containers\Instagram\Tasks\GetListContactTask;
use App\Containers\Instagram\Tasks\RedirectClientTask;
use App\Containers\Instagram\Tasks\GetTimeLineTask;
use App\Containers\Instagram\Tasks\GetThreadByParticipantsTask;
use Illuminate\Support\Facades\Input;
use InstagramAPI\Response;

//use App\Containers\Instagram\Tasks\GetTimeLineTask;
class InstagramHandler extends Action {
  public $ig;
  public function obtainComments(){
    $this->ig = $this->call(LoginInstagramTask::class,[]);
    $interactions = $this->call(RecentActivityTask::class,[$this->ig]);
    $this->call(ManageStoriesTask::class,[$interactions]);
    return 'Interacciones Almacenadas';
  }
  public function updateUsers(){
	$this->ig = $this->call(LoginInstagramTask::class,[]);
	$this->call(UpdateUsersAction::class,[$this->ig]);
  }
  public function obtainMessages(){
    $this->ig = $this->call(LoginInstagramTask::class,[]);
    $messages = $this->call(GetMessagesTask::class,[$this->ig]);
    $this->call(ManageMessagesTask::class,[$messages->inbox->threads]);
	$this->call(UpdateUsersAction::class,[$this->ig]);
    return 'Mensajes Directos Almacenadas';
  }

  public function getTimeLine(){
	$this->ig = $this->call(LoginInstagramTask::class,[]);
	//return 'sadasdasdasd';
	$pup = $this->call(GetThreadByParticipantsTask::class,[$this->ig, Input::get('thread')]);
	//return 'sadasdasdasd';
    return $this->call(GetTimeLineTask::class,[Input::get('thread')]);
  }

  public function sendMessage(){
    $this->ig = $this->call(LoginInstagramTask::class,[]);
    return $this->call(SendMessageTask::class,[$this->ig]);
  }
  public function listlikes(){
    return $this->call(ListLikesTask::class,[]);
  }
  public function listComments(){
    return $this->call(ListCommentsTask::class,[]);
  }
  public function listMessages(){
    return $this->call(ListMessagesTask::class,[]);
  }
  public function getDefMessages(){
    return $this->call(GetDefMessagesTask::class,[]);
  }
  public function changeStatus(){
    return $this->call(UpdateStatusTask::class,[]);
  }
  public function listContacts(){
    return $this->call(GetListContactTask::class,[]);
  }
  public function redirectClient(){
	$time_line = $this->getTimeLine();
    return $this->call(RedirectClientTask::class,[$time_line]);
  }
  public function getNotifications(){
    $messages = count($this->call(ListMessagesTask::class,[]));
    $comments = count($this->call(ListCommentsTask::class,[]));
    return array(
            'messages' => $messages,
            'comments'=> $comments,
          );
  }
}
