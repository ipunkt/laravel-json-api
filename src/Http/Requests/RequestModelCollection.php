<?php

namespace Ipunkt\LaravelJsonApi\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RequestModelCollection extends RequestModel
{
    /**
     * request models
     *
     * @var Collection|RequestModel[]
     */
    private $requestModels;

    /**
     * creates instance from data container
     *
     * @param array $data
     * @param Request|null $request
     * @return RequestModelCollection
     */
    public static function fromDataContainer(array $data, Request $request = null)
    {
        $models = collect();
        foreach ($data as $model) {

            $models->push((new RequestModel($request))->data(['data' => $model]));
        }
        return (new static($request))->models($models);
    }

    /**
     * sets data
     *
     * @param array $requestModels
     * @return self
     */
    public function models($requestModels) : self
    {
        $this->requestModels = $requestModels;

        return $this;
    }

    /**
     * returns a mapped collection
     *
     * @param callable $callback
     * @return Collection
     */
    public function map(callable $callback) {
        return $this->requestModels->map($callback);
    }

    /**
     * returns Id
     *
     * @return int[]
     */
    public function id()
    {
        return $this->requestModels->map(function (RequestModel $model) {
            return $model->id();
        })->all();
    }

    /**
     * request all representation
     *
     * @return array
     */
    public function all() : array
    {
        return $this->requestModels->map(function (RequestModel $model) {
            return $model->all();
        })->all();
    }
}