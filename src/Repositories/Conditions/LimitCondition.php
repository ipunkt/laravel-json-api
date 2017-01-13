<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class LimitCondition implements RepositoryCondition
{
    /**
     * limit
     * @var int
     */
    private $limit;

    /**
     * LimitCondition constructor.
     * @param int $limit
     * @param int $defaultLimit
     */
    public function __construct($limit, $defaultLimit = 50)
    {
        $this->limit = $limit ?? $defaultLimit;
    }

    /**
     * returns limit
     *
     * @return int
     */
    public function limit(): int
    {
        return intval($this->limit);
    }

    /**
     * sets limit
     *
     * @param int $limit
     * @return LimitCondition
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * apply a builder
     *
     * @param TakesConditions $builder
     */
    function apply(TakesConditions $builder)
    {
        $builder->limit($this->limit);
    }
}
