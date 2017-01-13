<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions;

interface RepositoryCondition
{
    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    function apply(TakesConditions $builder);
}
