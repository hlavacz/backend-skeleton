<?php

namespace App\Libs;

use Nette\Application\IPresenter;
use Nette\Application\Response;
use Nette\Application\Request;
use Nette\Application\UI\ComponentReflection;
use Nette\Application\UI\ITemplateFactory;
use Nette\DI\ServiceCreationException;
use Nette\Security\User;
use Tracy\Debugger;


define('ERRNO_NO_ACTION', 3000);
define('ERRNO_LOGIN_FAILED', 3001);


abstract class Module implements IPresenter
{

    public array $response = [];
    private int $responseCode = 200;
    private Request $request;
    private User $user;

    /**
     * Access to reflection.
     */
    public static function getReflection(): ComponentReflection
    {
        return new ComponentReflection(static::class);
    }


    /**
     * @throws \ReflectionException
     */
    public function run(Request $request): Response
    {

        $this->request = $request;
        $action = $this->request->getParameter('action');
        $rc = $this->getReflection();
        $payload = $this->getPayloadData();
        if ($payload && $rc->hasMethod('run' . $action)) {
            $rm = $rc->getMethod('run' . $action);
            $rm->invokeArgs($this, $payload);
        } else {
            $this->runBlackHole();
        }
        return new ApiResponse($this->response, $this->responseCode);
    }

    public function injectPrimary(User $user = null): void
    {
        $this->user = $user;
    }


    public function runBlackHole()
    {
        if (Debugger::$productionMode) $this->responseCode = 400;
        $this->response = ['status' => 'error', 'errno' => ERRNO_NO_ACTION, 'message' => 'No run for action'];
    }


    protected function getPayloadData(): ?array
    {
        $request_body = file_get_contents('php://input');
        return json_decode($request_body, true);
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        if (!$this->user) {
            throw new ServiceCreationException('Service User has not been set.');
        }
        return $this->user;
    }
}
