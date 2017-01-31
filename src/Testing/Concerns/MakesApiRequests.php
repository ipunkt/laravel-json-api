<?php

namespace Ipunkt\LaravelJsonApi\Testing\Concerns;

trait MakesApiRequests
{
    use ModifiesRequestHeaders;

    /**
     * makes api GET request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     */
    protected function api(string $uri, array $requestModel = [], array $headers = [])
    {
        return $this->json('GET', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /**
     * makes api POST request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     */
    protected function apiPost(string $uri, array $requestModel = [], array $headers = [])
    {
        return $this->json('POST', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /**
     * makes api PATCH request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     */
    protected function apiPatch(string $uri, array $requestModel = [], array $headers = [])
    {
        return $this->json('PATCH', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /**
     * makes api DELETE request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     */
    protected function apiDelete(string $uri, array $requestModel = [], array $headers = [])
    {
        return $this->json('DELETE', $uri, $requestModel, array_merge($this->headers(), $headers));
    }
}