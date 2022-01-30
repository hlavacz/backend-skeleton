<?php
declare(strict_types=1);

namespace App\Router;


use Nette;
use Nette\Routing\Router;

class ApiRoute implements Router
{
    private Router $innerRouter;
    private string $method;

    public function __construct(string $mask, string $method, string $metadata)
    {
        $this->innerRouter = new Nette\Application\Routers\Route($mask, $metadata);
        $this->method = $method;
    }

    function match(Nette\Http\IRequest $httpRequest): ?array
    {
        // options allowed due to CORS
        if (!in_array($httpRequest->getMethod(),[$this->method, 'OPTIONS'])) {
            return NULL;
        }
        return $this->innerRouter->match($httpRequest);
    }

    function constructUrl(array $params, Nette\Http\UrlScript $refUrl): ?string
    {
        return NULL;
    }
}
