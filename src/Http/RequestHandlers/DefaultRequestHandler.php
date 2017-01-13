<?php

namespace Ipunkt\LaravelJsonApi\Http\RequestHandlers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ipunkt\LaravelJsonApi\Contracts\OneToManyRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\OneToOneRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\ConditionAwareRepository;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesCollectionRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesItemRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipCollectionRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipItemRequest;
use Tobscure\JsonApi\Parameters;

/**
 * @parameter INDEX number? page[limit] Limitierung der Anzahl der Ergebnisse <xmp>50</xmp>
 * @parameter INDEX number? page[offset] Ab welchem Eintrag soll der Abruf begonnen werden <xmp>50</xmp>
 * @parameter INDEX string? sort Nach welchem Feld soll die Liste sortiert werden <xmp>publishDate</xmp>
 * @parameter ITEM number id Id der Ressource <xmp>1</xmp>
 */
class DefaultRequestHandler extends RequestHandler implements
    HandlesCollectionRequest,
    HandlesItemRequest,
    HandlesRelationshipCollectionRequest,
    HandlesRelationshipItemRequest
{
    /**
     * index request
     *
     * @param JsonApiRepository $repository
     * @param Parameters $parameters
     * @return Model[]|\Serializable[]|Collection|array
     */
    public function index(JsonApiRepository $repository, Parameters $parameters)
    {
        $this->applyFilters($this->filters, $this->filterFactory, $repository);

        return $repository->get();
    }

    /**
     * @param int|string $id
     * @param \Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository $repository
     * @param Parameters $parameters
     * @return Model|mixed
     */
    public function handle($id, JsonApiRepository $repository, Parameters $parameters)
    {
        $this->applyFilters($this->filters, $this->filterFactory, $repository);

        return $repository->findOrFail($id);
    }

    /**
     * relationship index request
     *
     * @param Model|mixed $resourceModel
     * @param RelatedRepository $repository
     * @return Model|Model[]|Collection|mixed
     */
    public function relatedCollection($resourceModel, RelatedRepository $repository)
    {
        if ($repository instanceof ConditionAwareRepository) {
            $this->applyFilters($this->filters, $this->filterFactory, $repository);
        }

        if ($repository instanceof OneToOneRelationRepository) {
            return $repository->getOne($resourceModel);
        }

        if ($repository instanceof OneToManyRelationRepository) {
            return $repository->getMany($resourceModel);
        }

        throw new \InvalidArgumentException('Repository ' . get_class($repository) . ' has no relation repository interface defined.');
    }

    /**
     * relationship item request
     *
     * @param Model|mixed $resourceModel
     * @param string|int $relatedId
     * @param RelatedRepository $repository
     * @return Model|mixed
     */
    public function relatedItem($resourceModel, $relatedId, RelatedRepository $repository)
    {
        if ($repository instanceof JsonApiRepository) {
            $this->applyFilters($this->filters, $this->filterFactory, $repository);
        }

        return $repository->findRelated($resourceModel, $relatedId);
    }
}
