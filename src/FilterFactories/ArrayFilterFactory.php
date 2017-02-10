<?php

namespace Ipunkt\LaravelJsonApi\FilterFactories;

use Illuminate\Contracts\Foundation\Application;
use Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\Conditions\RepositoryCondition;
use Ipunkt\LaravelJsonApi\Repositories\Conditions\IdIsCondition;

class ArrayFilterFactory implements FilterFactory
{
    /**
     * filters
     *
     * @var string[]
     */
    protected $filters = [
        'id' => IdIsCondition::class,
    ];

    /**
     * default filter
     *
     * @var string
     */
    protected $defaultFilter = null;

    /**
     * application / di
     *
     * @var Application
     */
    private $application;

    /**
     * ArrayFilterFactory constructor.
     * @param string[] $filters
     * @param string $defaultFilter
     * @param Application $application
     */
    public function __construct($filters = [], $defaultFilter = null, Application $application)
    {
        $this->filters = array_merge($this->filters, $filters);
        $this->defaultFilter = $defaultFilter ?? $this->defaultFilter;
        $this->application = $application;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return RepositoryCondition
     * @throws FilterFactoryNotFoundException
     */
    public function make($name, $value)
    {
        if (!$this->hasFilter($name) && $this->defaultFilter === null) {
            throw FilterFactoryNotFoundException::forFilter($name);
        }

        if (!$this->hasFilter($name)) {
            $condition = $this->application->make($this->defaultFilter);
            if ($condition instanceof RepositoryCondition) {
                $condition->setParameter($name, $value);
            }
            return $condition;
        }

        $classPath = $this->getFilter($name);
        $condition = $this->application->make($classPath);
        if ($condition instanceof RepositoryCondition) {
            $condition->setParameter($name, $value);
        }
        return $condition;
    }

    /**
     * returns all known filter with their corresponding class
     * Format: 'filtername' => 'Classpath'
     *
     * @return array|string[]
     */
    public function allAvailable()
    {
        return $this->filters;
    }

    /**
     * returns default filter
     *
     * @return null|string
     */
    public function getDefaultFilter()
    {
        return $this->defaultFilter;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function hasFilter($name)
    {
        return array_key_exists($name, $this->filters);
    }

    /**
     * @param $name
     * @return string
     */
    private function getFilter($name)
    {
        return $this->filters[$name];
    }
}
