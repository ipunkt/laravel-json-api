<?php

namespace Ipunkt\LaravelJsonApi\Repositories\Conditions;

use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions;

class WithinTimePeriodCondition implements RepositoryCondition
{
    /**
     *
     *
     * @var string
     */
    private $fromFieldName;

    /**
     *
     *
     * @var Carbon
     */
    private $fromFieldValue;

    /**
     *
     *
     * @var string
     */
    private $untilFieldName;

    /**
     *
     *
     * @var Carbon
     */
    private $untilFieldValue;

    /**
     * WithinTimePeriodCondition constructor.
     * @param string $fromFieldName
     * @param Carbon|Expression|string $fromFieldValue
     * @param string $untilFieldName
     * @param Carbon|Expression|string $untilFieldValue
     */
    public function __construct(string $fromFieldName, $fromFieldValue, string $untilFieldName, $untilFieldValue)
    {
        $this->fromFieldName = $fromFieldName;
        $this->fromFieldValue = ($fromFieldValue instanceof Carbon) ? $fromFieldValue->toDateTimeString() : $fromFieldValue;
        $this->untilFieldName = $untilFieldName;
        $this->untilFieldValue = ($untilFieldValue instanceof Carbon) ? $untilFieldValue->toDateTimeString() : $untilFieldValue;
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
        $this->fromFieldName = $name;
        $this->untilFieldName = $name;

        $this->fromFieldValue = $this->untilFieldValue = $value;

        return $this;
    }

    /**
     * apply a builder
     *
     * @param \Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\TakesConditions $builder
     */
    function apply(TakesConditions $builder)
    {
        $builder
            ->where($this->fromFieldName, '<=', $this->fromFieldValue)
            ->where($this->untilFieldName, '>=', $this->untilFieldValue);
    }
}