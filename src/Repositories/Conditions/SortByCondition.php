<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class SortByCondition implements RepositoryCondition
{
    /**
     * @var string
     */
    private $field;
    /**
     * @var bool
     */
    private $descending;

    /**
     * SortByCondition constructor.
     * @param string $field
     * @param bool $descending
     */
    public function __construct($field, $descending = false)
    {
        $this->field = $field;
        $this->descending = $descending;
    }

    /**
     * sets parameter
     *
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     */
    function setParameter($name, $value)
    {
        $this->field = $name;
        $this->descending = boolval($value);
        return $this;
    }

    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    function apply(TakesConditions $builder)
    {
        $builder->orderBy($this->field, $this->descending);
    }
}
