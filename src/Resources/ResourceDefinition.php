<?php

namespace Ipunkt\LaravelJsonApi\Resources;

use Illuminate\Support\Collection;

class ResourceDefinition
{
    /**
     * resource version
     *
     * @var
     */
    public $version;

    /**
     * resource name
     *
     * @var string
     */
    public $resource;

    /**
     * serializer
     *
     * @var string
     */
    public $serializer;

    /**
     * repository
     *
     * @var string
     */
    public $repository;

    /**
     * filter factory
     *
     * @var string
     */
    public $filterFactory;

    /**
     * request handler
     *
     * @var string
     */
    public $requestHandler;

    /**
     * relations
     *
     * @var \Illuminate\Support\Collection
     */
    public $relations;

	/**
	 * The types this resource(relation) can provide
	 *
	 * @var string[]|Collection
	 */
	public $types;

    /**
     * additional middleware for resource
     *
     * @var Collection
     */
    public $middleware;

    /**
     * description
     *
     * @var string
     */
    protected $description;

    /**
     * ResourceDefinition constructor.
     * @param int $version
     * @param string $resource
     * @param string $description
     */
    public function __construct(int $version, string $resource, string $description = null)
    {
        $this->version = $version;
        $this->resource = $resource;
        $this->relations = collect();
        $this->description = $description;
        $this->middleware = collect();
	    $this->types = collect($resource);
    }

    /**
     * sets serializer
     *
     * @param string $serializer
     * @return $this
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * sets repository
     *
     * @param string $repository
     * @return $this
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * sets filterFactory
     *
     * @param string $filterFactory
     * @return $this
     */
    public function setFilterFactory($filterFactory)
    {
        $this->filterFactory = $filterFactory;
        return $this;
    }

    /**
     * sets requestHandler
     *
     * @param string $requestHandler
     * @return $this
     */
    public function setRequestHandler($requestHandler)
    {
        $this->requestHandler = $requestHandler;
        return $this;
    }

    /**
     * adds middleware
     *
     * @param string $middleware
     * @return ResourceDefinition
     */
    public function addMiddleware(string $middleware) : self
    {
        $this->middleware->push($middleware);
        return $this;
    }

    /**
     * sets middleware
     *
     * @param string|array|Collection $middleware
     * @return ResourceDefinition
     */
    public function setMiddleware($middleware) : self
    {
        if (is_string($middleware)) {
            $middleware = [$middleware];
        }
        $this->middleware = collect()->merge($middleware);
        return $this;
    }

    /**
     * do we have middleware definition in resource definition
     *
     * @return bool
     */
    public function hasMiddleware() : bool
    {
        return !$this->middleware->isEmpty();
    }

    /**
     * defines a relation
     *
     * @param string $resource
     * @param \Closure $closure
     * @param string $description
     * @return $this
     */
    public function defineRelation(string $resource, \Closure $closure, string $description = null)
    {
        $this->relations->push(new ResourceDefinition($this->version, $resource, $description));

        $resourceDefinition = $this->relation($resource, $this->version);
        call_user_func_array($closure, [$resourceDefinition]);

        return $this;
    }

    /**
     * returns relation by resource and version
     *
     * @param string $resource
     * @param int|null $version
     * @return ResourceDefinition
     */
    public function relation(string $resource, int $version = null) : ResourceDefinition
    {
        $version = $version ?? $this->version;

        $found = $this->relations->filter(function (ResourceDefinition $definition) use ($resource, $version) {
            return $definition->version === $version && $definition->resource === $resource;
        });

        if ($found->isEmpty()) {
            throw ResourceNotDefinedException::resource($resource, 'version ' . $version);
        }

        return $found->first();
    }

    /**
     * returns a list of all related resources
     *
     * @return Collection
     */
    public function relatedResources() : Collection
    {
        return $this->relations->pluck('resource');
    }

    /**
     * returns description string
     *
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

	/**
	 * @param \string[] $types
	 * @return ResourceDefinition
	 */
	public function setTypes(...$types): ResourceDefinition {
		$this->types = collect($types);
		return $this;
	}

	/**
	 * @return \string[]|Collection
	 */
	public function types():Collection {
		return $this->types;
	}
}