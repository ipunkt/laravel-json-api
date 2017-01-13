<?php

namespace Ipunkt\LaravelJsonApi\Resources;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;
use Ipunkt\LaravelJsonApi\FilterFactories\ArrayFilterFactory;
use Ipunkt\LaravelJsonApi\Http\RequestHandlers\DefaultRequestHandler;
use Ipunkt\LaravelJsonApi\Serializers\Serializer;
use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\SerializerInterface;

class ResourceManager
{
    /**
     * definitions
     *
     * @var \Illuminate\Support\Collection
     */
    private $definitions;

    /**
     * dependency injection
     *
     * @var Application
     */
    private $app;

    /**
     * current version for fluent interface
     *
     * @var int
     */
    private $version;

    /**
     * ResourceManager constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->definitions = collect();
    }

    /**
     * defines a new resource
     *
     * @param string $resource
     * @param int|callable|\Closure $versionOrClosure
     * @param null|callable|\Closure $closureOrDescription
     * @param string $description
     * @return ResourceManager|static
     */
    public function define(string $resource, $versionOrClosure, $closureOrDescription = null, string $description = null)
    {
        $version = $this->version;
        $closure = $closureOrDescription;

        if ($versionOrClosure instanceof \Closure) {
            $version = $this->version;
            $closure = $versionOrClosure;
            $description = $closureOrDescription;
        }

        if (is_numeric($versionOrClosure)) {
            $version = intval($versionOrClosure);
        }

        $this->definitions->push(new ResourceDefinition($version, $resource, $description));

        $resourceDefinition = $this->definition($resource, $version);
        call_user_func_array($closure, [$resourceDefinition]);

        return $this;
    }

    /**
     * returns definition for resource and version
     *
     * @param string $resource
     * @param int $version
     * @return ResourceDefinition
     * @throws ResourceNotDefinedException when no definition found
     */
    public function definition(string $resource, int $version = null) : ResourceDefinition
    {
        $version = $version ?? $this->version;

        if (str_contains($resource, '.')) {
            list($resource, $related) = explode('.', $resource);
            $definition = $this->definition($resource, $version);

            $relatedDefinition = $definition->relations->filter(function (ResourceDefinition $definition) use ($related, $version) {
                return $definition->version === $version && $definition->resource === $related;
            });

	        if($relatedDefinition->isEmpty())
		        throw ResourceNotDefinedException::resource($resource, 'version ' . $version);

            return $relatedDefinition->first();
        }

        $found = $this->definitions->filter(function (ResourceDefinition $definition) use ($resource, $version) {
            return $definition->version === $version && $definition->resource === $resource;
        });

        if ($found->isEmpty()) {
            throw ResourceNotDefinedException::resource($resource, 'version ' . $version);
        }

        return $found->first();
    }

    /**
     * set version for fluent interface
     *
     * @param int $version
     * @return ResourceManager
     */
    public function version(int $version) : ResourceManager
    {
        $this->version = $version;

        return $this;
    }

    /**
     * returns all available versions
     *
     * @return Collection
     */
    public function versions()
    {
        return $this->definitions->unique('version')->pluck('version');
    }

    /**
     * returns all resources
     *
     * @param int $version
     * @return Collection
     */
    public function resources(int $version = null)
    {
        $version = $version ?? $this->version;

        return $this->definitions->filter(function (ResourceDefinition $definition) use ($version) {
            return $definition->version === $version;
        })->pluck('resource');
    }

    /**
     * resolves a definition or related definition
     *
     * @param string $type
     * @param string $resource
     * @param array $parameters
     * @param string|null $version
     * @return mixed|string
     */
    public function resolve(string $type, string $resource, array $parameters = [], $version = null)
    {
        $version = $version ?? $this->version;

        if (str_contains($resource, '.')) {
            list($resource, $related) = explode('.', $resource);
            $resourceDefinition = $this->loadRelatedDefinition($version, $resource, $related, $type);
        } else {
            $resourceDefinition = $this->loadDefinition($version, $resource, $type);
        }

        return $this->app->make($resourceDefinition, $parameters);
    }

    /**
     * resolves a serializer
     *
     * @param string $resource
     * @param array $parameters
     * @param int $version
     * @return SerializerInterface|AbstractSerializer|Serializer
     * @throws ResourceNotDefinedException when no serializer configured
     */
    public function resolveSerializer($resource, array $parameters = [], $version = null)
    {
        return $this->resolve('serializer', $resource, $parameters, $version);
    }

    /**
     * resolves a repository
     *
     * @param string $resource
     * @param array $parameters
     * @param null|string $version
     * @return \Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository|RelatedRepository
     * @throws ResourceNotDefinedException when no repository configured
     */
    public function resolveRepository($resource, array $parameters = [], $version = null)
    {
        return $this->resolve('repository', $resource, $parameters, $version);
    }

    /**
     * resolves a controller
     *
     * @param string $resource
     * @param array $parameters
     * @param null|string $version
     * @param bool $resolveDefaultRequestHandler
     * @return \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler
     */
    public function resolveRequestHandler(
        $resource,
        array $parameters = [],
        $version = null,
        $resolveDefaultRequestHandler = true
    )
    {
        try {
            return $this->resolve('requestHandler', $resource, $parameters, $version);
        } catch (ResourceNotDefinedException $e) {
            if ($resolveDefaultRequestHandler) {
                return $this->app->make(DefaultRequestHandler::class, $parameters);
            }
            throw $e;
        }
    }

    /**
     * resolves a filter factory
     *
     * @param string $resource
     * @param array $parameters
     * @param null|string $version
     * @return \Ipunkt\LaravelJsonApi\Contracts\FilterFactories\FilterFactory
     */
    public function resolveFilterFactory($resource, array $parameters = [], $version = null)
    {
        try {
            return $this->resolve('filterFactory', $resource, $parameters, $version);
        } catch (ResourceNotDefinedException $e) {
            return $this->app->make(ArrayFilterFactory::class, $parameters);
        }
    }

    /**
     * loads a definition
     *
     * @param string $version
     * @param string $resource
     * @param string $type
     * @param string $key
     * @return string
     */
    private function loadDefinition($version, $resource, $type, $key = null) : string
    {
        $definition = $this->definition($resource, $version);

        $classPath = $definition->$type;
        if (is_array($classPath) && array_key_exists($key, $classPath)) {
            $classPath = $classPath[$key];
        }

        if ($classPath === null) {
            throw ResourceNotDefinedException::resource($resource, $type);
        }

        return $classPath;
    }

    /**
     * loads a related definition
     *
     * @param string $version
     * @param string $resource
     * @param string $related
     * @param string $type
     * @param string $key
     * @return string
     */
    private function loadRelatedDefinition($version, $resource, $related, $type, $key = null) : string
    {
        $definition = $this->definition($resource, $version);
        $relationDefinition = $definition->relation($related);

        $classPath = $relationDefinition->$type;
        if (is_array($classPath) && array_key_exists($key, $classPath)) {
            $classPath = $classPath[$key];
        }

        if ($classPath === null) {
            throw ResourceNotDefinedException::resource($resource, $type);
        }

        return $classPath;
    }
}