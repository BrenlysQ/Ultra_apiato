<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Commons\CommonActions;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CredentialsTaks extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($currency)
    {
      $credentials = CommonActions::CreateObject();

      if($currency == 3){
        $credentials->ISOCountry = "VE";
        $credentials->TerminalID = getenv('KIU_MYC_TERMINALID');
        $credentials->ISOCurrency = "VEF";
        $credentials->UserID = getenv('KIU_MYC_USERID');
        $credentials->PseudoCityCode = "MYC";
      }else{
        $credentials->ISOCountry = "PA";
        $credentials->TerminalID = getenv('KIU_PTY_TERMINALID');
        $credentials->ISOCurrency = "USD";
        $credentials->UserID = getenv('KIU_PTY_USERID');
        $credentials->PseudoCityCode = "PTY";
      }

      return $credentials;
                     
    }

}
