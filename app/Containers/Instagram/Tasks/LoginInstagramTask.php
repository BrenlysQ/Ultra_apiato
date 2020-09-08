<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use InstagramAPI\Instagram as Instagram;

class LoginInstagramTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public $debug = false;
    public $truncateDebug = false;

    public function run()
    {
        $ig = new Instagram($this->debug, $this->truncateDebug);
        try {
          $ig->login(getenv('INSTA_USER'),getenv('INSTA_PASSWORD'));
        } catch (\Exception $e) {
          echo 'Something went wrong: '.$e->getMessage()."\n";
          exit(0);
        }
        return $ig;
    }

}
