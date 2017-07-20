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
    public function where($fieldOrClosure, $operatorOrValue = null, $value = null);

    /**
     * @param string $field
     * @param array $possibleValues
     * @return $this
     */
    public function whereIn($field, $possibleValues);

    /**
     * @param string $string
     * @param mixed $param
     * @return $this
     */
    public function whereHas($string, $param);

    /**
     * @param string $field
     * @param bool $descending defaults to false
     * @return $this
     */
    public function orderBy($field, $descending = false);

    /**
     * @param int $limit
     * @return $this
     */
    public function limit($limit);

    /**
     * @param string $field
     * @return $this
     */
    public function whereNotNull($field);

    /**
     * @param string $field
     * @return $this
     */
    public function whereNull($field);

    /**
     * @param string|\Closure $fieldOrClosure
     * @param string|mixed $operatorOrValue
     * @param mixed $value
     * @return $this
     */
    public function orWhere($fieldOrClosure, $operatorOrValue = null, $value = null);

    /**
     * @param mixed $query
     * @return $this
     */
    public function select($query);

    /**
     * @param string $field
     * @param float $value
     * @return $this
     */
    public function boost($field, $value);

    /**
     * @param int $offset
     * @return $this
     */
    public function offset($offset);

    /**
     * @param string $name
     * @param string $field
     * @param int $min
     * @param null $limit
     * @return $this
     */
    public function addFacet($name, $field, $min = null, $limit = null);

    /**
     * @param string $name
     * @param string $field
     * @param int $start
     * @param mixed $gap
     * @param mixed $end
     * @return $this
     */
    public function addFacetRange($name, $field, $start, $gap, $end);

    /**
	 * Also search for related items with the next query
	 *
	 * @param $fields
	 * @param int $minimumDocFrequency
	 * @param int $minimumTermFrequency
	 * @param int $count
	 * @return $this
	 */
	public function addMoreLikeThis($fields, $minimumDocFrequency = null, $minimumTermFrequency = null, $count = null);

	/**
	 * @param $table
	 * @param $field1
	 * @param $condition
	 * @param $field2
	 * @return mixed
	 */
	public function join($table, $field1, $condition, $field2);
}
