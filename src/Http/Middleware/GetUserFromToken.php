<?php

namespace Ipunkt\LaravelJsonApi\Http\Middleware;

use Ipunkt\LaravelJsonApi\Exceptions\JsonApiError;

class GetUserFromToken extends \Tymon\JWTAuth\Middleware\GetUserFromToken
{
    /**
     * Fire event and return the response.
     *
     * @param  string $event
     * @param  string $error
     * @param  int $status
     * @param  array $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        $jsonApiError = new JsonApiError(str_replace('_', ' ', $error));
        $jsonApiError->setStatusCode($status);

        return $response ?: $this->response->json(['errors' => [$jsonApiError]], $status);
    }
}