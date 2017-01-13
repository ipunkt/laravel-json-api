<?php

namespace Ipunkt\LaravelJsonApi\Serializers;

use Illuminate\Database\Eloquent\Model;
use Ipunkt\LaravelJsonApi\Contracts\OneToManyRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\OneToOneRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\ConditionAwareRepository;
use Ipunkt\LaravelJsonApi\Resources\ResourceManager;
use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

abstract class Serializer extends AbstractSerializer
{
    /**
     * Resource Manager
     *
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * Serializer constructor.
     * @param ResourceManager $resourceManager
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    /**
     * returns Id of the model
     *
     * @param Model|mixed $model
     * @return mixed
     */
    public function getId($model)
    {
        return $model->getKey();
    }

    /**
     * {@inheritdoc}
     *
     * @param Model|mixed $model
     */
    public function getAttributes($model, array $fields = null)
    {
        $attributes = $this->attributes($model);

        if ($fields !== null) {
            $attributes = array_only($attributes, $fields);
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationship($model, $name)
    {
        try {
            //$resource = request()->route('resource') ?? $this->type;
            $resource = $this->getType($model);
            $repository = $this->resourceManager->resolveRepository($resource . '.' . $name, [],
                request()->route('version'));
            $serializer = $this->resourceManager->resolveSerializer($resource . '.' . $name, [],
                request()->route('version'));

            $this->applyFilters($resource, $name, $repository);

            if ($repository instanceof OneToOneRelationRepository) {
                $resourceOrCollection = new Resource($repository->getOne($model), $serializer);

                if ($resourceOrCollection->getData() === null) {
                    return null;
                }
            } elseif ($repository instanceof OneToManyRelationRepository) {
                $resourceOrCollection = new Collection($repository->getMany($model), $serializer);
            } else {
                throw new \InvalidArgumentException('Repository ' . get_class($repository) . ' has not the correct interface implemented');
            }

            return new Relationship($resourceOrCollection);
        } catch (\Exception $e) {
            throw new \LogicException('Resource ' . $name . ' as related resource not defined', 0, $e);
        }
    }

    /**
     * returns attributes for model
     *
     * @param Model $model
     * @return array
     */
    abstract protected function attributes($model): array;

    /**
     * @param string $resource
     * @param $name
     * @param $repository
     */
    private function applyFilters(string $resource = null, string $name = null, $repository)
    {
        if (!$repository instanceof ConditionAwareRepository) {
            return;
        }

        $filterFactory = $this->resourceManager->resolveFilterFactory($resource . '.' . $name, [],
            request()->route('version'));

        $filters = \RelationshipFilterParser::filtersForRelationship($resource, $name, request()->input('filter'));
        \FilterApplier::applyFiltersToRepository($filters, $filterFactory, $repository);
    }
}