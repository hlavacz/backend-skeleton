<?php
declare(strict_types=1);

namespace App\Modules;

use App\Libs\Module;
use Nette\Security\AuthenticationException;

final class AclModule extends Module
{

    public function beforeRun()
    {
        if ($this->getAction() === 'login') {
            $this->authenticated = true;
        }
    }


    public function runLogin(array $user)
    {
        $username = $user['username'];
        $password = $user['password'];
        try {
            $this->getUser()->login($username,$password);
        } catch (AuthenticationException $exception) {
            $this->response = [
                'status'=>'error',
                'errno'=>ERRNO_LOGIN_FAILED
            ];
        }
    }
}
