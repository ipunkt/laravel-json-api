<?php

namespace Ipunkt\LaravelJsonApi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheResponse
{
    /**
     * cache instance
     *
     * @var \Illuminate\Cache\Repository
     */
    private $cache;

    /**
     * cachable?
     *
     * @var bool
     */
    private $cachable = null;

    /**
     * minutes to cache
     *
     * @var int
     */
    private $minutesToCache = 0;

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @param int $minutes minutes to cache
     * @return mixed
     */
    public function handle($request, Closure $next, $minutes = 0)
    {
        $this->minutesToCache = $minutes;

        if ($this->isCachable($request)) {
            $cacheKey = $this->cacheKey($request);
            if ($this->cache()->has($cacheKey)) {
                return $this->cache()->get($cacheKey);
            }
        }

        return $next($request);
    }

    /**
     *
     *
     * @param Request $request
     * @param \Illuminate\Http\Response $response
     */
    public function terminate($request, $response)
    {
        if (!$this->isCachable($request)) {
            return;
        }
        if (!$response->isOk()) {
            return;
        }

        $cacheKey = $this->cacheKey($request);

        //  cache headers to cache
        $response->header('X-Served-By', 'Cache');

        $this->cache()->put($cacheKey, $response, $this->minutesToCache);
    }

    /**
     * is current request cachable?
     *
     * @param Request $request
     * @return bool
     */
    private function isCachable(Request $request)
    {
        if ($this->cachable === null) {
            $this->cachable = $request->method() === 'GET' && !app()->environment('local') && $this->minutesToCache > 0;
        }

        return $this->cachable;
    }

    /**
     * returns cache repository
     *
     * @return \Illuminate\Cache\Repository
     */
    private function cache()
    {
        if ($this->cache === null) {
            $this->cache = app('cache');
        }
        return $this->cache;
    }

    /**
     * returns cache key
     *
     * @param Request $request
     * @return string
     */
    private function cacheKey(Request $request)
    {
        $url = $request->fullUrl();
        return 'api-' . $url;
    }
}
