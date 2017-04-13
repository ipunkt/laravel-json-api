<?php

namespace Ipunkt\LaravelJsonApi\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;
use Ipunkt\LaravelJsonApi\Repositories\Conditions\ConditionTaker;

class Repository implements JsonApiRepository
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * with eager loadings
     *
     * @var array
     */
    protected $with = array();

    /**
     * @var \Ipunkt\LaravelJsonApi\Repositories\Conditions\ConditionApplier
     */
    protected $conditionApplier;

    /**
     * default sort criteria, when nothing given
     *
     * Format: 'fieldName' => 'asc', // or 'desc'
     *
     * @var array
     */
    protected $defaultSortCriterias = [];

    /**
     * sort criterias
     *
     * Format: 'attributeNameInRequest' => 'field_name_in_database'
     *
     * @var array
     */
    protected $sortCriterias = [];

	/**
	 * Will be used by all $query->get() calls:
	 * `$query->get( $getParameters )`
	 * Usecase: restrict fields to the model table when using joins: $queryParamaters = ['table.*'];
	 *
	 * @var null|array
	 */
    protected $getParameters = null;

    /**
     * returns a collection of all models
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * returns the model found
     *
     * @param int $id
     * @return Model
     */
    public function find($id)
    {
        $query = $this->make();
        return $query->find($id);
    }

    /**
     * finds a model by id, fails with ModelNotFoundException when not found
     *
     * @param int|string $id
     * @return Model
     */
    function findOrFail($id)
    {
        return $this->make()->findOrFail($id);
    }

    /**
     * returns the repository itself, for fluent interface
     *
     * @param array $with
     * @return self
     */
    public function with(array $with)
    {
        $this->with = array_merge($this->with, $with);

        return $this;
    }

    /**
     * returns the first model found by conditions
     *
     * @param string $key
     * @param mixed $value
     * @param string $operator
     * @return Model
     */
    public function findFirstBy($key, $value, $operator = '=')
    {
        $query = $this->make();
        return $query->where($key, $operator, $value)->first();
    }

    /**
     * returns all models found by conditions
     *
     * @param string $key
     * @param mixed $value
     * @param string $operator
     * @return Collection
     */
    public function findAllBy($key, $value, $operator = '=')
    {
        $query = $this->make();
        return $query->where($key, $operator, $value)->get( $this->getParameters );
    }

    /**
     * returns all models that have a required relation
     *
     * @param string $relation
     * @return Collection
     */
    public function has($relation)
    {
        $query = $this->make();
        return $query->has($relation)->get( $this->getParameters );
    }

    /**
     * returns paginated result
     *
     * @param int $page
     * @param int $limit
     * @return PaginatedResult
     */
    public function getPaginated($page = 1, $limit = 10)
    {
        $query = $this->make();
        $collection = $query->forPage($page, $limit)->get();
        return new PaginatedResult($page, $limit, $collection->count(), $collection);
    }

    /**
     * @param \Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition $constraint
     * @return void
     */
    function applyCondition(RepositoryCondition $constraint)
    {
        $this->conditionApplier->addCondition($constraint);
    }

    /**
     * returns a collection
     *
     * @return Model[]|\Serializable[]|Collection|array
     */
    function get()
    {
        return $this->makeQuery()->get( $this->getParameters );
    }

    /**
     * Returns the total count of objects
     *
     * @return int
     */
    function count()
    {
        return $this->makeQuery()->count();
    }

    /**
     * Prevents the next request from clearing the conditions. Meant to use with count
     *
     * @param bool $peek defaults to true if not given
     * @return void
     */
    function setPeek($peek = true)
    {
        $this->conditionApplier->setPeek($peek === true);
    }

    /**
     * Map of available sort criterias
     * 'sort_name_from_request' => 'database_field_name'
     *
     * @return array
     */
    function sortCriterias() : array
    {
        return $this->sortCriterias;
    }

    /**
     * default sort criteria, when nothing given
     *
     * Format: 'fieldName' => 'asc', // or 'desc'
     *
     * @return array
     */
    function defaultSortCriterias() : array
    {
        return $this->defaultSortCriterias;
    }

    /**
     * returns the query builder with eager loading, or the model itself
     *
     * @return Builder|Model
     */
    protected function make()
    {
        return $this->model->with($this->with);
    }

    /**
     * returns a query builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeQuery()
    {
        $query = $this->make();
        $this->conditionApplier->apply(new ConditionTaker($query));

        return $query;
    }

	/**
	 * @param array $relation
	 */
    public function eagerLoad(...$relation) {
	    if( !is_array($relation) ) {
		    $this->with[] = $relation;
		    return;
	    }

		$this->with = array_merge($this->with, $relation);
    }
}
