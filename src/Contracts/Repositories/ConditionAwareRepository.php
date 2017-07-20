<?php

namespace Ipunkt\LaravelJsonApi\Contracts\Repositories;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;

interface ConditionAwareRepository
{
    /**
     * @param RepositoryCondition $constraint
     * @return void
     */
    public function applyCondition(RepositoryCondition $constraint);
}
