<?php

namespace Ipunkt\LaravelJsonApi\Http\Controllers;

use Illuminate\Http\Request;
use Ipunkt\LaravelJsonApi\Contracts\OneToManyRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\OneToOneRelationRepository;
use Ipunkt\LaravelJsonApi\Contracts\RelatedRepository;
use Ipunkt\LaravelJsonApi\Contracts\Repositories\JsonApiRepository;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesCollectionRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesDeleteRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesItemRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesPatchRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesPostRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipCollectionRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipDeleteRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipItemDeleteRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipItemRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipPatchRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipPostRequest;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HasDocumentMeta;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HasElementIncludes;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ModifiesSerializer;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\NeedsAuthenticatedUser;
use Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest;
use Ipunkt\LaravelJsonApi\Repositories\Conditions\LimitCondition;
use Ipunkt\LaravelJsonApi\Repositories\Conditions\OffsetCondition;
use Ipunkt\LaravelJsonApi\Repositories\Conditions\SortByCondition;
use Ipunkt\LaravelJsonApi\Resources\ResourceManager;
use Ipunkt\LaravelJsonApi\Resources\ResourceNotDefinedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\ElementInterface;
use Tobscure\JsonApi\Parameters;
use Tobscure\JsonApi\Resource;

class JsonApiController extends Controller
{
    /**
     * JsonApiController constructor.
     * @param Request $request
     * @param ResourceManager $resourceManager
     */
    public function __construct(Request $request, ResourceManager $resourceManager)
    {
        $resource = $request->route('resource');
        $version = $request->route('version');

        if ($resource !== null && $version !== null) {
            try {
                $definition = $resourceManager->definition($resource, $version);
                if ($definition->hasMiddleware()) {
                    $this->middleware($definition->middleware->all());
                }
            } catch (ResourceNotDefinedException $e) {
            }
        }
    }

    /**
     * handles a collection/index request to a resource
     *
     * /public/v1/resource
     * /secure/v1/resource
     *
     * @param ResourceManager $resourceManager
     * @param ApiRequest $request
     * @param int $version
     * @param string $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection(
        ResourceManager $resourceManager,
        ApiRequest $request,
        int $version,
        string $resource
    )
    {
        $resourceManager->version($version);

        /** @var HandlesCollectionRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource, $request, HandlesCollectionRequest::class);

        $parameters = $request->asParameters();

        $repository = $this->initializeRepository($resourceManager, $resource, $parameters);

        $result = $handler->index($repository, $parameters);

        $document = $this->makeDocumentFromCollection($resourceManager, $resource, $result, $parameters, $handler);

        return $this->respond($document);
    }

    /**
     * handles an item request to a resource
     *
     * /public/v1/resource/id
     * /secure/v1/resource/id
     *
     * @param ResourceManager $resourceManager
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param string|int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function item(ResourceManager $resourceManager, ApiRequest $request, int $version, string $resource, $id)
    {
        $resourceManager->version($version);

        /** @var \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesItemRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource, $request, HandlesItemRequest::class);

        $parameters = $request->asParameters();

        $repository = $this->initializeRepository($resourceManager, $resource, $parameters);

        $result = $handler->handle($id, $repository, $parameters);

        if ($result instanceof \Illuminate\Support\Collection) {
            $document = $this->makeDocumentFromCollection($resourceManager, $resource, $result, $parameters, $handler);
        } else {
            $document = $this->makeDocumentFromResource($resourceManager, $resource, $result, $parameters, $handler);
        }

        return $this->respond($document);
    }

    /**
     * handles a store/creation request
     *
     * /secure/v1/resource
     *
     * @param ResourceManager $resourceManager
     * @param ApiRequest $request
     * @param int $version
     * @param string $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ResourceManager $resourceManager, ApiRequest $request, int $version, string $resource)
    {
        $resourceManager->version($version);

        /** @var HandlesPostRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource, $request, HandlesPostRequest::class);

        $parameters = $request->asParameters();

        $result = $handler->store($request, $parameters);

        if ($result === null) {
            return $this->respondNoContent();
        }

        $document = $this->makeDocumentFromResource($resourceManager, $resource, $result, $parameters, $handler);

        return $this->respondCreated($document);
    }

    /**
     * returns patch response for patch request
     *
     * @param ResourceManager $resourceManager
     * @param ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param string|int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch(ResourceManager $resourceManager, ApiRequest $request, int $version, string $resource, $id)
    {
        $resourceManager->version($version);

        /** @var \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesPatchRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource, $request, HandlesPatchRequest::class);

        $parameters = $request->asParameters();

        $result = $handler->patch($id, $request, $parameters);

        if ($result === true) {
            return $this->respondNoContent();
        }
        if ($result === false) {
            //  @TODO: Result false means nothing changed! Result okay? Wishful changes are set, so okay is fine.
            return $this->respondNoContent();
        }

        $document = $this->makeDocumentFromResource($resourceManager, $resource, $result, $parameters, $handler);

        return $this->respond($document);
    }

    /**
     * returns patch response for patch request
     *
     * @param ResourceManager $resourceManager
     * @param ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param string|int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(ResourceManager $resourceManager, ApiRequest $request, int $version, string $resource, $id)
    {
        $resourceManager->version($version);

        /** @var \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesDeleteRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource, $request, HandlesDeleteRequest::class);

        $handler->delete($id, $request);

        return $this->respondNoContent();
    }

    /**
     * handles relationship call and prepares a handle() call
     *
     * @param ResourceManager $resourceManager
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param integer|string $id
     * @param string $relationship
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedCollection(
        ResourceManager $resourceManager,
        ApiRequest $request,
        int $version,
        string $resource,
        $id,
        string $relationship
    )
    {
        $resourceManager->version($version);

        /** @var \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipCollectionRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource . '.' . $relationship, $request,
            HandlesRelationshipCollectionRequest::class);

        $parameters = $request->asParameters();

        $resourceRepository = $this->initializeRepository($resourceManager, $resource, $parameters);
        $model = $resourceRepository->findOrFail($id);

        $repository = $resourceManager->resolveRepository($resource . '.' . $relationship);

        $result = $handler->relatedCollection($model, $repository);

        if ($repository instanceof OneToOneRelationRepository) {
            $document = $this->makeDocumentFromResource($resourceManager, $resource . '.' . $relationship, $result,
                $parameters, $handler);
        } elseif ($repository instanceof OneToManyRelationRepository) {
            $document = $this->makeDocumentFromCollection($resourceManager, $resource . '.' . $relationship, $result,
                $parameters, $handler);
        } else {
            throw new NotFoundHttpException('Resource not defined');
        }

        if (config('json-api.response.relationships.links.self', false)) {
            $document->addLink('self', apiRouteRelationship($resource, $id, $relationship, null, $version));
        }
        if (config('json-api.response.relationships.links.related', false)) {
            $document->addLink('related', apiRoute($resource, $id));
        }

        return $this->respond($document);
    }

    /**
     * handles relationship item request
     *
     * /public/v1/resources/{id}/relationships/{related}/{relatedId}
     *
     * @param ResourceManager $resourceManager
     * @param ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param string|int $id
     * @param string $relationship
     * @param string|int $parameter
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedItem(
        ResourceManager $resourceManager,
        ApiRequest $request,
        int $version,
        string $resource,
        $id,
        string $relationship,
        $parameter
    )
    {
        $resourceManager->version($version);

        /** @var HandlesRelationshipItemRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource . '.' . $relationship, $request,
            HandlesRelationshipItemRequest::class);

        $parameters = $request->asParameters();

        $resourceRepository = $this->initializeRepository($resourceManager, $resource, $parameters);
        $model = $resourceRepository->findOrFail($id);

        $repository = $resourceManager->resolveRepository($resource . '.' . $relationship);

        $result = $handler->relatedItem($model, $parameter, $repository);

        $document = $this->makeDocumentFromResource($resourceManager, $resource . '.' . $relationship, $result,
            $parameters, $handler);

        if (config('json-api.response.relationships.item.links.self', false)) {
            $document->addLink('self', apiRouteRelationship($resource, $id, $relationship, null, $version));
        }
        if (config('json-api.response.relationships.item.links.related', false)) {
            $document->addLink('related', apiRoute($resource, $id));
        }

        return $this->respond($document);
    }

    /**
     * handles relationship post call and prepares a handle() call
     *
     * @param ResourceManager $resourceManager
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param integer|string $id
     * @param string $relationship
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedPost(
        ResourceManager $resourceManager,
        ApiRequest $request,
        int $version,
        string $resource,
        $id,
        string $relationship
    )
    {
        $resourceManager->version($version);

        /** @var \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipPostRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource . '.' . $relationship, $request,
            HandlesRelationshipPostRequest::class);

        $parameters = $request->asParameters();

        $resourceRepository = $this->initializeRepository($resourceManager, $resource, $parameters);
        $model = $resourceRepository->findOrFail($id);

        $result = $handler->relatedPost($request, $model);

        if (empty($result)) {
            return $this->respondNoContent();
        }

        if ($result instanceof \Illuminate\Support\Collection) {
            $document = $this->makeDocumentFromCollection($resourceManager, $resource . '.' . $relationship, $result,
                $parameters, $handler);
        } else {
            $document = $this->makeDocumentFromResource($resourceManager, $resource . '.' . $relationship, $result,
                $parameters, $handler);
        }

        return $this->respondCreated($document);
    }

    /**
     * handles relationship patch call and prepares a handle() call
     *
     * @param ResourceManager $resourceManager
     * @param \Ipunkt\LaravelJsonApi\Http\Requests\ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param integer|string $id
     * @param string $relationship
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedPatch(
        ResourceManager $resourceManager,
        ApiRequest $request,
        int $version,
        string $resource,
        $id,
        string $relationship
    )
    {
        $resourceManager->version($version);

        /** @var \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\HandlesRelationshipPatchRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource . '.' . $relationship, $request,
            HandlesRelationshipPatchRequest::class);

        $parameters = $request->asParameters();

        $resourceRepository = $this->initializeRepository($resourceManager, $resource, $parameters);
        $model = $resourceRepository->findOrFail($id);

        $result = $handler->relatedPatch($request, $model);

        if (empty($result)) {
            return $this->respondNoContent();
        }

        if ($result instanceof \Illuminate\Support\Collection) {
            $document = $this->makeDocumentFromCollection($resourceManager, $resource . '.' . $relationship, $result,
                $parameters, $handler);
        } else {
            $document = $this->makeDocumentFromResource($resourceManager, $resource . '.' . $relationship, $result,
                $parameters, $handler);
        }

        return $this->respond($document);
    }
	/**
	 * handles relationship item delete request (To-One Relation)
	 *
	 * /public/v1/resources/{id}/relationships/{related}
	 * /secure/v1/resources/{id}/relationships/{related}
	 *
	 * @param ResourceManager $resourceManager
	 * @param ApiRequest $request
	 * @param int $version
	 * @param string $resource
	 * @param string|int $id
	 * @param string $relationship
	 * @param string|int $parameter
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function relatedDelete(
		ResourceManager $resourceManager,
		ApiRequest $request,
		int $version,
		string $resource,
		$id,
		string $relationship
	) {
		$resourceManager->version($version);

		/** @var HandlesRelationshipDeleteRequest $handler */
		$handler = $this->initializeHandler($resourceManager, $resource . '.' . $relationship, $request,
			HandlesRelationshipDeleteRequest::class);

		$parameters = $request->asParameters();

		$resourceRepository = $this->initializeRepository($resourceManager, $resource, $parameters);
		$model = $resourceRepository->findOrFail($id);

		$repository = $resourceManager->resolveRepository($resource . '.' . $relationship);

		$handler->relatedDelete($model, $repository);

		return $this->respondNoContent();
	}

    /**
     * handles relationship item delete request (To-Many Relation)
     *
     * /public/v1/resources/{id}/relationships/{related}/{relatedId}
     * /secure/v1/resources/{id}/relationships/{related}/{relatedId}
     *
     * @param ResourceManager $resourceManager
     * @param ApiRequest $request
     * @param int $version
     * @param string $resource
     * @param string|int $id
     * @param string $relationship
     * @param string|int $parameter
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedItemDelete(
        ResourceManager $resourceManager,
        ApiRequest $request,
        int $version,
        string $resource,
        $id,
        string $relationship,
        $parameter
    )
    {
        $resourceManager->version($version);

        /** @var HandlesRelationshipItemDeleteRequest $handler */
        $handler = $this->initializeHandler($resourceManager, $resource . '.' . $relationship, $request,
            HandlesRelationshipItemDeleteRequest::class);

        $parameters = $request->asParameters();

        $resourceRepository = $this->initializeRepository($resourceManager, $resource, $parameters);
        $model = $resourceRepository->findOrFail($id);

        $repository = $resourceManager->resolveRepository($resource . '.' . $relationship);

        $handler->relatedItemDelete($model, $parameter, $repository);

        return $this->respondNoContent();
    }

    /**
     * assigns resolved includes from request
     *
     * @param ResourceManager $resourceManager
     * @param string $resource
     * @param ElementInterface $resourceOrCollection
     * @param Parameters $parameters
     */
    private function assignAndResolveIncludes(
        ResourceManager $resourceManager,
        string $resource,
        ElementInterface $resourceOrCollection,
        Parameters $parameters
    )
    {

	    $relateds = collect();

	    $availableIncludes = $this->retrieveRelationsRecursive($resource, $relateds, $resourceManager);

        $resourceOrCollection->with($parameters->getInclude($availableIncludes->all()));
    }

	/**
	 * @param string $resource
	 * @param \Illuminate\Support\Collection $relationTree
	 * @param \Illuminate\Support\Collection $alreadyKnownRelations
	 * @param ResourceManager $resourceManager
	 */
    private function retrieveRelationsRecursive($resource, &$alreadyKnownRelations, ResourceManager $resourceManager) {

	    try {
		    $availableIncludes = $resourceManager->definition($resource)->relatedResources();
	    } catch(ResourceNotDefinedException $e) {
		    return collect();
	    }

	    foreach($availableIncludes as $include) {

		    try {

			    $relationName = $resource . '.' . $include;

			    $definition = $resourceManager->definition( $relationName );

		    } catch(ResourceNotDefinedException $e) {
			    continue;
		    }


		    foreach($definition->types() as $type) {

			    if($alreadyKnownRelations->has($type) )
				    continue;

			    $alreadyKnownRelations->put($type, true);

			    $subIncludes = $this->retrieveRelationsRecursive($type, $alreadyKnownRelations, $resourceManager);
			    foreach( $subIncludes as $subInclude )
			    	$availableIncludes->push( $type.'.'.$subInclude );

		    }


	    }

	    return $availableIncludes;

    }


    /**
     * returns initialized handler
     *
     * @param ResourceManager $resourceManager
     * @param string $resource
     * @param ApiRequest $request
     * @param string|null $checkInterface
     * @return \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler
     */
    private function initializeHandler(
        ResourceManager $resourceManager,
        string $resource,
        ApiRequest $request,
        string $checkInterface = null
    )
    {
        $handler = $resourceManager->resolveRequestHandler($resource);
        if ($handler instanceof NeedsAuthenticatedUser && !\Auth::check()) {
            throw new HttpException(401, 'Unauthorized Request');
        }

        if ($checkInterface !== null) {
            if (!$handler instanceof $checkInterface) {
                throw new HttpException(400, 'Bad Request');
            }
        }

        $filterFactory = $resourceManager->resolveFilterFactory($resource);

        $handler
            ->setRequest($request)
            ->setFilters($request->filters())
            ->setFilterFactory($filterFactory);

        return $handler;
    }

    /**
     * initialize repositories
     *
     * @param ResourceManager $resourceManager
     * @param string $resource
     * @param Parameters $parameters
     * @return RelatedRepository|JsonApiRepository
     */
    private function initializeRepository(ResourceManager $resourceManager, string $resource, Parameters $parameters)
    {
        $repository = $resourceManager->resolveRepository($resource);

        $repository->applyCondition(new LimitCondition($this->getLimit($parameters)));
        $offset = $parameters->getOffset();
        if (!empty($offset)) {
            $repository->applyCondition(new OffsetCondition($offset));
        }

        $sortCriterias = $repository->sortCriterias();
        $sort = $parameters->getSort(array_keys($sortCriterias));
        if (count($sort)) {
            foreach ($sort as $field => $direction) {
                $repository->applyCondition(new SortByCondition($sortCriterias[$field], $direction === 'desc'));
            }
        } else {
            foreach ($repository->defaultSortCriterias() as $field => $direction) {
                $repository->applyCondition(new SortByCondition($field, $direction === 'desc'));
            }
        }

        return $repository;
    }

    /**
     * returns limit
     *
     * @param Parameters $parameters
     * @param int|null $maxLimit
     * @return int
     */
    private function getLimit(Parameters $parameters, int $maxLimit = null) : int
    {
        $maxLimit = $maxLimit ?? config('json-api.defaults.max-limit', 50);
        $limit = $parameters->getLimit($maxLimit);

        return $limit ?? $maxLimit;
    }

    /**
     * makes a document from collection
     *
     * @param ResourceManager $resourceManager
     * @param string $resource
     * @param mixed $result
     * @param Parameters $parameters
     * @param \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler $handler
     * @return Document
     */
    private function makeDocumentFromCollection(
        ResourceManager $resourceManager,
        string $resource,
        $result,
        Parameters $parameters,
        ApiRequestHandler $handler
    )
    {
        $document = new Document();
        $serializer = $resourceManager->resolveSerializer($resource);
        if ($handler instanceof ModifiesSerializer) {
            $serializer = $handler->modify($serializer);
        }
        $collection = new Collection($result, $serializer);
        if ($handler instanceof HasElementIncludes) {
            $handler->includes($collection);
        }

        $this->assignAndResolveIncludes($resourceManager, $resource, $collection, $parameters);
        $document->setData($collection);

        if ($handler instanceof HasDocumentMeta) {
            $handler->meta($document);
        }

        if (config('json-api.response.resources.links.self', false)) {
            $document->addLink('self', apiRoute($resource, null, request()->route('version')));
        }

        return $document;
    }

    /**
     * makes a document from resource
     *
     * @param ResourceManager $resourceManager
     * @param string $resource
     * @param mixed $result
     * @param Parameters $parameters
     * @param \Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler $handler
     * @return Document
     */
    private function makeDocumentFromResource(
        ResourceManager $resourceManager,
        string $resource,
        $result,
        Parameters $parameters,
        ApiRequestHandler $handler
    )
    {
        $document = new Document();
        $serializer = $resourceManager->resolveSerializer($resource);
        if ($handler instanceof ModifiesSerializer) {
            $serializer = $handler->modify($serializer);
        }
        $element = new Resource($result, $serializer);
        if ($handler instanceof HasElementIncludes) {
            $handler->includes($element);
        }

        $this->assignAndResolveIncludes($resourceManager, $resource, $element, $parameters);
        $document->setData($element);

        if ($handler instanceof HasDocumentMeta) {
            $handler->meta($document);
        }

        if (config('json-api.response.resources.item.links.self', false)) {
            $document->addLink('self', apiRoute($resource, null, request()->route('version')));
        }

        return $document;
    }
}
