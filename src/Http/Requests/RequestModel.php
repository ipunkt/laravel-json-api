<?php

namespace Ipunkt\LaravelJsonApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;

class RequestModel
{
    /**
     * type
     *
     * @var string
     */
    private $type;

    /**
     * id
     *
     * @var int
     */
    private $id;

    /**
     * attributes
     *
     * @var array
     */
    private $attributes = [];

    /**
     * api version
     *
     * @var string
     */
    private $version = '1.0';

    /**
     * meta data
     *
     * @var array
     */
    private $meta = array();

    /**
     * base request
     *
     * @var Request
     */
    private $request;

    /**
     * RequestModel constructor.
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?: app('request');
    }

    /**
     * creates instance from request
     *
     * @param FormRequest $request
     * @return static|RequestModelCollection
     */
    public static function fromRequest($request = null)
    {
        $request = $request ?: app('request');

        /** @var ParameterBag $data */
        $data = $request->json();

        $dataContainer = $data->get('data', []);
        if (array_has($dataContainer, '0')) {
            return RequestModelCollection::fromDataContainer($dataContainer, $request);
        }

        return (new static($request))->data($data->all());
    }

    /**
     * set data
     *
     * @param array $data
     * @return self
     */
    public function data($data) : self
    {
        $this->parseData($data);

        return $this;
    }

    /**
     * returns request
     *
     * @return Request
     */
    public function request() : Request
    {
        return $this->request;
    }

    /**
     * returns NewsItemType
     *
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * returns Id
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * request all representation
     *
     * @return array
     */
    public function all() : array
    {
        return [
            'id' => $this->id(),
            'type' => $this->type(),
            'attributes' => $this->attributes()
        ];
    }

    /**
     * request has key
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key) : bool
    {
        return array_has($this->attributes(), $key);
    }

    /**
     * returns Attributes
     *
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * returns Attributes
     *
     * @param string $key
     * @param null|mixed $default
     * @return string|mixed
     */
    public function attribute($key, $default = null)
    {
        return array_get($this->attributes, $key, $default);
    }

    /**
     * returns Version
     *
     * @return string
     */
    public function apiVersion()
    {
        return $this->version;
    }

    /**
     * returns Meta
     *
     * @param null|string $key
     * @return array|string
     */
    public function meta($key = null)
    {
        if ($key !== null) {
            return array_get($this->meta, $key);
        }

        return $this->meta;
    }

    /**
     * returns callback result
     *
     * @param callable $callback
     * @return mixed|Collection
     */
    public function map(callable $callback) {
        return call_user_func($callback, $this);
    }

    /**
     * parses data
     *
     * @param array $data
     */
    private function parseData(array $data)
    {
        $this->type = array_get($data, 'data.type');
        $this->id = array_get($data, 'data.id');

        $this->attributes = array_get($data, 'data.attributes');

        $this->version = array_get($data, 'jsonapi.version', $this->version);

        $this->meta = array_get($data, 'meta');
    }
}
