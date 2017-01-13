<?php

namespace Ipunkt\LaravelJsonApi\Repositories;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;

class FindCacheRepositoryDecorator implements JsonApiRepository
{
    /**
     * @var JsonApiRepository
     */
    private $decorated;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var string
     */
    private $keyPrefix;

    /**
     * @var int
     */
    private $minutesToCache;

    /**
     * @var bool
     */
    private $refresh = false;

    /**
     * @var array
     */
    private $with = [];

    /**
     * FindCacheRepositoryDecorator constructor.
     * @param $keyPrefix
     * @param JsonApiRepository $decorated
     * @param Cache $cache
     */
    public function __construct($keyPrefix, JsonApiRepository $decorated, Cache $cache)
    {
        $this->decorated = $decorated;
        $this->cache = $cache;
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @param RepositoryCondition $constraint
     * @return void
     */
    public function applyCondition(RepositoryCondition $constraint)
    {
        $this->decorated->applyCondition($constraint);
    }

    /**
     * finds a model by id
     *
     * @param int $id
     * @return Model|\Serializable
     */
    public function find($id)
    {
        if ($this->refresh) {
            $this->forget($id);
        }
        return $this->cache->remember($this->keyPrefix . $id . implode('-', $this->with), $this->minutesToCache,
            function () use ($id) {
                return $this->decorated->find($id);
            });
    }

    /**
     * finds a model by id, fails with ModelNotFoundException when not found
     *
     * @param int $id
     * @return Model|\Serializable
     */
    public function findOrFail($id)
    {
        if ($this->refresh) {
            $this->forget($id);
        }
        return $this->cache->remember($this->keyPrefix . $id, $this->minutesToCache, function () use ($id) {
            return $this->decorated->findOrFail($id);
        });
    }

    /**
     * returns a collection
     *
     * @return Model[]|\Serializable[]|Collection|array
     */
    public function get()
    {
        return $this->decorated->get();
    }

    /**
     * Returns the total count of objects
     *
     * @return int
     */
    public function count()
    {
        return $this->decorated->count();
    }

    /**
     * Prevents the next request from clearing the conditions. Meant to use with count
     *
     * @param bool $peek defaults to true if not given
     * @return void
     */
    public function setPeek($peek = true)
    {
        $this->decorated->setPeek($peek);
    }

    /**
     * Map of available sort criterias
     * ['sort_name_from_request' => 'database_field_name']
     *
     * @return array
     */
    public function sortCriterias()
    {
        return $this->decorated->sortCriterias();
    }

    /**
     * default sort criteria, when nothing given
     *
     * Format: ['fieldName' => 'asc'], // or 'desc'
     *
     * @return array
     */
    public function defaultSortCriterias()
    {
        return $this->decorated->defaultSortCriterias();
    }

    /**
     * @param int $minutesToCache
     * @return $this
     */
    public function setMinutesToCache(int $minutesToCache)
    {
        $this->minutesToCache = $minutesToCache;
        return $this;
    }

    /**
     * @param bool $isRefresh
     * @return $this
     */
    public function setRefresh(bool $isRefresh)
    {
        $this->refresh = $isRefresh;
        return $this;
    }

    /**
     * @param array ...$relation
     */
    public function eagerLoad(...$relation)
    {
        $this->with = array_merge($this->with, $relation);
        $this->decorated->eagerLoad(...$relation);
    }

    /**
     * @param $id
     */
    public function forget($id)
    {
        $this->cache->forget($this->keyPrefix . $id . implode('-', $this->with));
    }
}