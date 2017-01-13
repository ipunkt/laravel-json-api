<?php

namespace Ipunkt\LaravelJsonApi\Routing;

use Illuminate\Database\Eloquent\Model;

class UrlGenerator
{
    /**
     * version
     *
     * @var int
     */
    private $version;

    /**
     * resource
     *
     * @var string
     */
    private $resource;

    /**
     * resource id
     *
     * @var string|int
     */
    private $id;

    /**
     * secure route
     *
     * @var bool
     */
    private $secure = false;

    /**
     * relationship resource
     *
     * @var string
     */
    private $relationship;

    /**
     * related resource id
     *
     * @var int|string
     */
    private $relatedId;

    /**
     * sets version
     *
     * @param int $version
     * @return UrlGenerator
     */
    public function version($version) : self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * sets resource
     *
     * @param string $resource
     * @return UrlGenerator
     */
    public function resource(string $resource) : self
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * sets resource id
     *
     * @param int|string|Model $id
     * @return UrlGenerator
     */
    public function resourceId($id) : self
    {
        $this->id = ($id instanceof \Illuminate\Database\Eloquent\Model) ? $id->getKey() : $id;

        return $this;
    }

    /**
     * set secure route
     *
     * @return UrlGenerator
     */
    public function secure() : self
    {
        $this->secure = true;

        return $this;
    }

    /**
     * set public route
     *
     * @return UrlGenerator
     */
    public function public () : self
    {
        $this->secure = false;

        return $this;
    }

    /**
     * sets relationship
     *
     * @param string $relationship
     * @return UrlGenerator
     */
    public function relationship(string $relationship) : self
    {
        $this->relationship = $relationship;

        return $this;
    }

    /**
     * sets related id
     *
     * @param int|string $relatedId
     * @return UrlGenerator
     */
    public function relatedId(string $relatedId = null) : self
    {
        $this->relatedId = $relatedId;

        return $this;
    }

    /**
     * resets all settings
     *
     * @return UrlGenerator
     */
    public function reset() : self
    {
        $this->version = null;
        $this->resource = null;
        $this->id = null;
        $this->secure = false;
        $this->relationship = null;
        $this->relatedId = null;

        return $this;
    }

    /**
     * generates url
     *
     * @return string
     */
    public function generate() : string
    {
        if ($this->version === null) {
            $this->version = request()->route('version');
        }

        $routeName = $this->assembleRouteName();

        $routeParams = $this->assembleRouteParams();

        $url = config('app.url') . route($routeName, $routeParams, false);

        $this->reset();

        return $url;
    }

    /**
     * assembles route name
     *
     * @return string
     */
    private function assembleRouteName() : string
    {
        $prefix = ($this->secure) ? 'secure-api' : 'api';

        $routeName = 'resource';

        if ($this->relatedId !== null) {
            $routeName = 'resource.relationship.item';
        } else {
            if ($this->relationship !== null) {
                $routeName = 'resource.relationship';
            } else {
                if ($this->id !== null) {
                    $routeName = 'resource.item';
                }
            }
        }

        return $prefix . '.' . $routeName;
    }

    /**
     * assembles route params
     *
     * @return array
     */
    private function assembleRouteParams() : array
    {
        $params = [
            'version' => $this->version,
            'resource' => $this->resource,
        ];

        if ($this->id !== null) {
            $params['id'] = $this->id;
        }

        if ($this->relationship !== null) {
            $params['relationship'] = $this->relationship;
        }

        if ($this->relatedId !== null) {
            $params['parameter'] = $this->relatedId;
        }

        return $params;
    }
}