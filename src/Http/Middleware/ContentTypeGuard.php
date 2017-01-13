<?php

namespace Ipunkt\LaravelJsonApi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ContentTypeGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $expectedType = ApiRequestHandler::CONTENT_TYPE;

        $contentType = explode(',', $request->header('Content-Type'));
        if (!in_array($expectedType, $contentType)) {
            throw new NotAcceptableHttpException('Not Acceptable');
        }

        $acceptedTypes = explode(',', $request->header('Accept'));
        if (!in_array($expectedType, $acceptedTypes)) {
            throw new NotAcceptableHttpException('Not Acceptable');
        }

        return $next($request);
    }
}
