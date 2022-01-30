<?php
declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;

final class ApiRouterFactory
{
    use Nette\StaticClass;

    public static function createRouter() : RouteList
    {
        $router = new RouteList;
        $router[] = new ApiRoute('/acl/login', 'POST', 'Acl:login');
        return $router;
    }
}
