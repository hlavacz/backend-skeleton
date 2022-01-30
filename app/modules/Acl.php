<?php
declare(strict_types=1);

namespace App\Modules;

use App\Libs\Module;
use Nette\Security\AuthenticationException;

final class AclModule extends Module
{

    public function runLogin($payload)
    {
        if (!isset($payload['username']) || !isset($payload['password'])) {
            $this->runBlackHole();
            exit();
        }
        try {
            $this->getUser()->login($payload['username'],$payload['password']);
        } catch (AuthenticationException $exception) {
            $this->response = [
                'status'=>'error',
                'errno'=>ERRNO_LOGIN_FAILED
            ];
        }
    }
}
