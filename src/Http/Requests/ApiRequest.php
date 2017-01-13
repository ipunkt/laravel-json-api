<?php

namespace Ipunkt\LaravelJsonApi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tobscure\JsonApi\Parameters;

class ApiRequest extends FormRequest
{
    /**
     * parameters instance
     *
     * @var Parameters
     */
    private $parameters;

    /**
     * request model
     *
     * @var RequestModel
     */
    private $requestModel;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * returns filter
     *
     * @return array
     */
    public function filters()
    {
        return $this->get('filter', []);
    }

    /**
     * is GET request?
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->method() === 'GET';
    }

    /**
     * is POST request?
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->method() === 'POST';
    }

    /**
     * returns as Parameters instance
     *
     * @return Parameters
     */
    public function asParameters() : Parameters
    {
        if ($this->parameters === null) {
            $this->parameters = new Parameters($this->all());
        }
        return $this->parameters;
    }

    /**
     * returns as Request Model
     *
     * @return RequestModel
     */
    public function asRequestModel() : RequestModel
    {
        if ($this->requestModel === null) {
            $this->requestModel = RequestModel::fromRequest($this);
        }
        return $this->requestModel;
    }
}
