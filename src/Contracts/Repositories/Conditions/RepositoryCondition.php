<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions;

interface RepositoryCondition
{
    /**
     * sets parameter
     *
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     */
    function setParameter($name, $value);

    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    function apply(TakesConditions $builder);
}
