<?php

namespace Ipunkt\LaravelJsonApi\Exceptions;

use Illuminate\Support\Collection;
use Ipunkt\LaravelJsonApi\Contracts\Exceptions\ExtendedLoggingInformation;

/**
 * Class JsonApiError
 * @package Ipunkt\LaravelJsonApi\Exceptions
 * @see http://jsonapi.org/format/1.0/#errors
 */
class JsonApiError implements \JsonSerializable
{
    /**
     * a unique identifier for this particular occurrence of the problem.
     *
     * @var string
     */
    private $id;

    /**
     * a links object containing the following members:
     * - about: a link that leads to further details about this particular occurrence of the problem.
     *
     * @var string
     */
    private $link;

    /**
     * the HTTP status code applicable to this problem, expressed as a string value.
     *
     * @var int
     */
    private $status = 400;

    /**
     * an application-specific error code, expressed as a string value.
     *
     * @var int
     */
    private $code;

    /**
     * a short, human-readable summary of the problem that SHOULD NOT change from occurrence to occurrence of the problem, except for purposes of localization.
     *
     * @var string
     */
    private $title;

    /**
     * a human-readable explanation specific to this occurrence of the problem. Like title, this field’s value can be localized.
     *
     * @var string
     */
    private $detail;

    /**
     * an object containing references to the source of the error, optionally including any of the following members:
     * - pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     * - parameter: a string indicating which URI query parameter caused the error.
     *
     * @var Collection
     */
    private $source;

    /**
     * a meta object containing non-standard meta-information about the error.
     *
     * @var array|Collection
     */
    private $meta;

    /**
     * exception
     *
     * @var \Exception
     */
    private $exception;

    /**
     * JsonApiError constructor.
     * @param string $title
     * @param int|null $code
     */
    public function __construct(string $title, int $code = null)
    {
        $this->title = $title;
        $this->code = $code;
    }

    /**
     * sets id
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * sets link
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * sets status
     *
     * @param int $status
     * @return $this
     */
    public function setStatusCode($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * returns status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * sets code
     *
     * @param int $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * sets title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * sets detail
     *
     * @param string $detail
     * @return $this
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * sets source
     *
     * @param array|Collection $source
     * @return $this
     */
    public function setSource($source)
    {
        if (!$source instanceof Collection) {
            $source = collect($source);
        }

        $this->source = $source;
        return $this;
    }

    /**
     * sets meta
     *
     * @param array|Collection $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * sets exception
     *
     * @param \Exception $exception
     * @return JsonApiError
     */
    public function setException(\Exception $exception) : self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * returns the JSON Api Error object as array
     *
     * @return array
     */
    public function toArray() : array
    {
        $result = [];

        if (!empty($this->id)) {
            $result['id'] = $this->id;
        }

        if (!empty($this->link)) {
            $result['links']['about'] = $this->link;
        }

        if (!empty($this->status)) {
            $result['status'] = "{$this->status}";
        }

        if (!empty($this->code)) {
            $result['code'] = "{$this->code}";
        }

        if (!empty($this->title)) {
            $result['title'] = $this->title;
        }

        if (!empty($this->detail)) {
            $result['detail'] = $this->detail;
        }

        if (!empty($this->source)) {
            $result['source'] = $this->source->map(function ($item) {
                return $item;
            });
        }

        if (!empty($this->meta)) {
            $result['meta'] = $this->meta;
        }

        if (!empty($this->exception)) {
            $e = $this->exception;
            if ($e instanceof ExtendedLoggingInformation) {
                $result['exception'] = $e->context();
            } else {
                $result['exception'] = [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'stacktrace' => $e->getTrace(),
                ];
            }
        }

        return $result;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }
}