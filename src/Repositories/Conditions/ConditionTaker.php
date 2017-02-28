<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

/**
 * Adapter zwischen dem TakesConditions Interface und dem Eloquent QueryBuilder
 */
class ConditionTaker implements TakesConditions
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * EloquentConditionTaker constructor.
     * @param Builder $builder
     */
    public function __construct(&$builder)
    {
        $this->builder = &$builder;
    }

    /**
     * @param string|\Closure $fieldOrClosure
     * @param mixed|string $operatorOrValue
     * @param mixed $value
     * @return $this
     */
    function where($fieldOrClosure, $operatorOrValue = null, $value = null)
    {
        $this->builder = $this->builder->where($fieldOrClosure, $operatorOrValue, $value);
        return $this;
    }

    /**
     * @param string $field
     * @param array $possibleValues
     * @return $this
     */
    function whereIn($field, $possibleValues)
    {
        $this->builder = $this->builder->whereIn($field, $possibleValues);
        return $this;
    }

    /**
     * @param string $string
     * @param mixed $param
     * @return $this
     */
    function whereHas($string, $param)
    {
        $this->builder = $this->builder->whereHas($string, $param);
        return $this;
    }

    /**
     * @param string $field
     * @param bool $descending defaults to false
     * @return $this
     */
    function orderBy($field, $descending = false)
    {
        $direction = $descending ? 'desc' : 'asc';

        $this->builder = $this->builder->orderBy($field, $direction);
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    function limit($limit)
    {
        $this->builder = $this->builder->limit($limit);
        return $this;
    }

    /**
     * @param string $field
     * @return $this
     */
    function whereNotNull($field)
    {
        $this->builder = $this->builder->whereNotNull($field);
        return $this;
    }

    /**
     * @param string $field
     * @return $this
     */
    function whereNull($field)
    {
        $this->builder = $this->builder->whereNull($field);
        return $this;
    }

    /**
     * @param string|\Closure $fieldOrClosure
     * @param string|mixed $operatorOrValue
     * @param mixed $value
     * @return $this
     */
    function orWhere($fieldOrClosure, $operatorOrValue = null, $value = null)
    {
        $this->builder = $this->builder->orWhere($fieldOrClosure, $operatorOrValue, $value);
        return $this;
    }

    /**
     * magic method on builder
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments) {
        return call_user_func_array( array($this->builder, $name), $arguments);
    }

    /**
     * @param mixed $query
     * @return $this
     */
    function select($query)
    {
        $this->builder->select($query);
        return $this;
    }

    /**
     * @param string $field
     * @param float $value
     * @return $this
     */
    function boost($field, $value)
    {
        throw new \InvalidArgumentException('boost');
    }

    /**
     * @param int $offset
     * @return $this
     */
    function offset($offset)
    {
        $this->builder->offset($offset);
    }

    /**
     * @param string $name
     * @param string $field
     * @param int $min
     * @param null $limit
     * @return $this
     */
    function addFacet($name, $field, $min = null, $limit = null)
    {
        return $this;
    }

    /**
     * @param string $name
     * @param string $field
     * @param int $start
     * @param mixed $gap
     * @param mixed $end
     * @return $this
     */
    function addFacetRange($name, $field, $start, $gap, $end)
    {
        return $this;
    }

	/**
	 * Eloquent Datenbankabfragen haben aktuell keine Möglichkeit generel nach ähnlichen Treffern zu suchen
	 *
	 * @param $fields
	 * @param null $minimumDocFrequency
	 * @param null $minimumTermFrequency
	 * @param null $count
	 * @return $this
	 */
	public function addMoreLikeThis($fields, $minimumDocFrequency = null, $minimumTermFrequency = null, $count = null) {
		return $this;
	}

	/**
	 * @param $table
	 * @param $field1
	 * @param $field2
	 * @return mixed
	 */
	public function join($table, $field1, $condition, $field2) {
		$this->builder->join($table, $field1, $condition, $field2);
	}
}
