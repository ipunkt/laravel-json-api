<?php

namespace Ipunkt\LaravelJsonApi\Testing\Concerns;

use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler;

trait ModifiesRequestHeaders
{
    use InteractsWithAuthentication;

    /**
     * returns headers
     *
     * @return array
     */
    protected function headers(): array
    {
        $headers = [
            'Accept' => ApiRequestHandler::CONTENT_TYPE,
            'Content-Type' => ApiRequestHandler::CONTENT_TYPE,
        ];

        if (!empty(static::$token)) {
            $headers['Authorization'] = 'Bearer ' . static::$token;
        }

        return $headers;
    }
}