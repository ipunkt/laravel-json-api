<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;

interface ConditionAwareRepository
{
    /**
     * @param RepositoryCondition $constraint
     * @return void
     */
    function applyCondition(RepositoryCondition $constraint);
}