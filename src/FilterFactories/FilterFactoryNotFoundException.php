<?php

namespace Ipunkt\LaravelJsonApi\FilterFactories;

use Ipunkt\LaravelJsonApi\Exceptions\JsonApiException;

class FilterFactoryNotFoundException extends JsonApiException
{
    /**
     * @var string
     */
    private $filter;

    /**
     * FilterFactoryNotFoundException constructor.
     * @param string $statusCode
     * @param string $message
     * @param string $filter
     */
    public function __construct($statusCode, $message, $filter)
    {
        $this->filter = $filter;

        parent::__construct($statusCode, $message);
    }

    /**
     * creates for filter
     *
     * @param string $filter
     * @return FilterFactoryNotFoundException
     */
    public static function forFilter($filter)
    {
        return new self(404, "Filter $filter not found", $filter);
    }

    /**
     * returns controller
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
