<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Illuminate\Support\Collection;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class ConditionApplier
{
    /**
     * @var Collection|RepositoryCondition[]
     */
    private $conditions;

    /**
     * @var bool
     */
    private $peek = false;

    /**
     * ConditionApplier constructor.
     */
    public function __construct()
    {
        $this->conditions = new Collection;
    }

    /**
     * adds a condition
     *
     * @param RepositoryCondition $condition
     */
    public function addCondition(RepositoryCondition $condition)
    {
        $this->conditions->push($condition);
    }

    /**
     * returns condition by type
     *
     * @param string $respositoryType
     * @return Collection
     */
    public function getConditionByType(string $respositoryType) : Collection
    {
        return $this->conditions->filter(function (RepositoryCondition $condition) use ($respositoryType) {
            return $condition instanceof $respositoryType;
        });
    }

    /**
     * Prevents the next request from clearing the Conditions if set to true
     *
     * @param bool $peek defaults to true if not give
     */
    public function setPeek($peek = null)
    {
        $this->peek = $peek ?? true;
    }

    /**
     * takes a condition
     *
     * @param \Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions $builder
     */
    public function apply(TakesConditions $builder)
    {
        $peeking = $this->peek;
        $this->peek = false;

        foreach ($this->conditions as $condition) {
            $condition->apply($builder);
        }
        // Clear Conditions
        if ($peeking) {
            return;
        }

        $this->conditions = new Collection();
    }

    /**
     * returns conditions
     *
     * @return Collection
     */
    public function conditions() : Collection
    {
        return $this->conditions;
    }
}
