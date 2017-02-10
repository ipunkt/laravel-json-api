<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class DefaultCondition implements RepositoryCondition
{
    /**
     * field name
     *
     * @var string
     */
    private $name;

    /**
     * field value
     *
     * @var mixed
     */
    private $value;

    /**
     * sets parameter
     *
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     */
    function setParameter($name, $value)
    {
        $this->name = $name;
        $this->value = $value;

        return $this;
    }

    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    function apply(TakesConditions $builder)
    {
        $builder->where($this->name, $this->value);
    }
}
