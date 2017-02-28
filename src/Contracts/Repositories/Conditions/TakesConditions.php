<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions;

interface TakesConditions
{
    /**
     * @param string|\Closure $fieldOrClosure
     * @param mixed|string $operatorOrValue
     * @param mixed $value
     * @return $this
     */
    function where($fieldOrClosure, $operatorOrValue = null, $value = null);

    /**
     * @param string $field
     * @param array $possibleValues
     * @return $this
     */
    function whereIn($field, $possibleValues);

    /**
     * @param string $string
     * @param mixed $param
     * @return $this
     */
    function whereHas($string, $param);

    /**
     * @param string $field
     * @param bool $descending defaults to false
     * @return $this
     */
    function orderBy($field, $descending = false);

    /**
     * @param int $limit
     * @return $this
     */
    function limit($limit);

    /**
     * @param string $field
     * @return $this
     */
    function whereNotNull($field);

    /**
     * @param string $field
     * @return $this
     */
    function whereNull($field);

    /**
     * @param string|\Closure $fieldOrClosure
     * @param string|mixed $operatorOrValue
     * @param mixed $value
     * @return $this
     */
    function orWhere($fieldOrClosure, $operatorOrValue = null, $value = null);

    /**
     * @param mixed $query
     * @return $this
     */
    function select($query);

    /**
     * @param string $field
     * @param float $value
     * @return $this
     */
    function boost($field, $value);

    /**
     * @param int $offset
     * @return $this
     */
    function offset($offset);

    /**
     * @param string $name
     * @param string $field
     * @param int $min
     * @param null $limit
     * @return $this
     */
    function addFacet($name, $field, $min = null, $limit = null);

    /**
     * @param string $name
     * @param string $field
     * @param int $start
     * @param mixed $gap
     * @param mixed $end
     * @return $this
     */
    function addFacetRange($name, $field, $start, $gap, $end);

	/**
	 * Also search for related items with the next query
	 *
	 * @param $fields
	 * @param int $minimumDocFrequency
	 * @param int $minimumTermFrequency
	 * @param int $count
	 * @return $this
	 */
	function addMoreLikeThis($fields, $minimumDocFrequency = null, $minimumTermFrequency = null, $count = null);

	/**
	 * @param $table
	 * @param $field1
	 * @param $condition
	 * @param $field2
	 * @return mixed
	 */
	function join($table, $field1, $condition, $field2);
}
