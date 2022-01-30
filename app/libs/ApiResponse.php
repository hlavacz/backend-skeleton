<?php
declare(strict_types=1);

namespace App\Libs;
use Nette;
use Nette\Application\Response;
use Nette\Application\Responses\JsonResponse;

/**
 * @method self deleteHeader(string $name)
 */
class ApiResponse implements Response {

    private int $responseCode;
    private JsonResponse $jsonResponse;

    public function __construct(array $payload, int $responseCode)
    {
        $this->responseCode = $responseCode;
        if (!isset($payload['status'])) {
            $payload['status'] = 'ok';
        }
        $this->jsonResponse = new JsonResponse($payload);
    }


    function send(Nette\Http\IRequest $httpRequest, Nette\Http\IResponse $httpResponse): void
    {
        $httpResponse->setCode($this->responseCode);
        $this->jsonResponse->send($httpRequest,$httpResponse);
    }
}
