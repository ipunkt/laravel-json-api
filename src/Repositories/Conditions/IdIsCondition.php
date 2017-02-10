<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class IdIsCondition implements RepositoryCondition
{
    protected $allowedIds = [];

    /**
     * sets parameter
     *
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     */
    function setParameter($name, $value)
    {
        $ids = explode(',', $value);

        //
        if (count($ids) < 2) {
            $ids = $id;
        }

        $this->allowedIds = $ids;

        return $this;
    }

    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    public function apply(TakesConditions $builder)
    {
        $allowedIDs = $this->allowedIds;

        if (!is_array($allowedIDs)) {
            $builder->where('id', $allowedIDs);

            return;
        }

        $builder->whereIn('id', $allowedIDs);
    }
}