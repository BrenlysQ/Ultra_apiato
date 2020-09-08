<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use InstagramAPI\Response;

class GetMessagesTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($ig)
    {
        $cursorId = null;
    	$request = $ig->request('direct_v2/inbox/')
            ->addParam('persistentBadging', 'true')
            ->addParam('use_unified_inbox', 'true');
        if ($cursorId !== null) {
            $request->addParam('cursor', $cursorId);
        }
        return $request->getResponse(new Response\DirectInboxResponse());
   	}
}
