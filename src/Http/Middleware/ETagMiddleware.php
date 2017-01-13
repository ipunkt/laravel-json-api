<?php

namespace Ipunkt\LaravelJsonApi\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ETagMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        if ($request->isMethod('get')) {
            // Generate Etag
            $etag = md5($response->getContent());

            $requestEtag = str_replace('"', '', $request->getETags());
            // Check to see if Etag has changed
            if ($requestEtag && $requestEtag[0] == $etag) {
                $response->setNotModified();
            }

            // Set Etag
            $response->setEtag($etag);
        }

        return $response;
    }
}
