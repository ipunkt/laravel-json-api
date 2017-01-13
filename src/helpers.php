<?php

if (!function_exists('apiRoute')) {
    /**
     * returns an api route
     *
     * @param string $resource
     * @param int|\Illuminate\Database\Eloquent\Model|null $id
     * @param int $version
     * @return string
     */
    function apiRoute(string $resource, $id = null, int $version = null) : string
    {
        $urlGenerator = app(\Ipunkt\LaravelJsonApi\Routing\UrlGenerator::class);

        return $urlGenerator->resource($resource)
            ->resourceId($id)
            ->version($version)
            ->generate();
    }
}

if (!function_exists('apiRouteRelationship')) {
    /**
     * returns an api route relationship
     *
     * @param string $resource
     * @param int|\Illuminate\Database\Eloquent\Model|string $id
     * @param string $relationship
     * @param int|\Illuminate\Database\Eloquent\Model|string|null $relatedId
     * @param int $version
     * @return string
     */
    function apiRouteRelationship(string $resource, $id, string $relationship, $relatedId = null, int $version = null) : string
    {
        $urlGenerator = app(\Ipunkt\LaravelJsonApi\Routing\UrlGenerator::class);

        return $urlGenerator->resource($resource)
            ->resourceId($id)
            ->relationship($relationship)
            ->relatedId($relatedId)
            ->version($version)
            ->generate();
    }
}

if (!function_exists('secureApiRoute')) {
    /**
     * returns a secure api route
     *
     * @param string $resource
     * @param int|\Illuminate\Database\Eloquent\Model|null $id
     * @param int $version
     * @return string
     */
    function secureApiRoute(string $resource, $id = null, int $version = 1) : string
    {
        $urlGenerator = app(\Ipunkt\LaravelJsonApi\Routing\UrlGenerator::class);

        return $urlGenerator->secure()
            ->resource($resource)
            ->resourceId($id)
            ->version($version)
            ->generate();
    }
}

if (!function_exists('secureApiRouteRelationship')) {
    /**
     * returns a secure api route relationship
     *
     * @param string $resource
     * @param int|\Illuminate\Database\Eloquent\Model|string $id
     * @param string $relationship
     * @param int|\Illuminate\Database\Eloquent\Model|string|null $relatedId
     * @param int $version
     * @return string
     */
    function secureApiRouteRelationship(string $resource, $id, string $relationship, $relatedId = null, int $version = null) : string
    {
        $urlGenerator = app(\Ipunkt\LaravelJsonApi\Routing\UrlGenerator::class);

        return $urlGenerator->secure()
            ->resource($resource)
            ->resourceId($id)
            ->relationship($relationship)
            ->relatedId($relatedId)
            ->version($version)
            ->generate();
    }
}
