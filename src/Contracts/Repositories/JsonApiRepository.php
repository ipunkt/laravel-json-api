<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;

interface JsonApiRepository extends ConditionAwareRepository
{
    /**
     * finds a model by id
     *
     * @param int $id
     * @return Model|\Serializable
     */
    function find($id);

    /**
     * finds a model by id, fails with ModelNotFoundException when not found
     *
     * @param int|string $id
     * @return Model|\Serializable
     */
    function findOrFail($id);

    /**
     * returns a collection
     *
     * @return Model[]|\Serializable[]|Collection|array
     */
    function get();

    /**
     * Returns the total count of objects
     *
     * @return int
     */
    function count();

    /**
     * Prevents the next request from clearing the conditions. Meant to use with count
     *
     * @param bool $peek defaults to true if not given
     * @return void
     */
    function setPeek($peek = true);

    /**
     * Map of available sort criterias
     * ['sort_name_from_request' => 'database_field_name']
     *
     * @return array
     */
    function sortCriterias();

    /**
     * default sort criteria, when nothing given
     *
     * Format: ['fieldName' => 'asc'], // or 'desc'
     *
     * @return array
     */
    function defaultSortCriterias();

	/**
	 * @param array ...$relation
	 */
	function eagerLoad(...$relation);
}
