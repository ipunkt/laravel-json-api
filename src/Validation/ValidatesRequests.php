<?php

namespace Ipunkt\LaravelJsonApi\Validation;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModel;
use Ipunkt\LaravelJsonApi\Http\Requests\RequestModelCollection;

trait ValidatesRequests
{
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    /**
     * Run the validation routine against the given validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator|array $validator
     * @param  RequestModel $request
     * @return void
     */
    public function validateWith($validator, RequestModel $request)
    {
        if (is_array($validator)) {
            $validator = $this->getValidationFactory()->make($request->all(), $validator);
        }

        if ($validator->fails()) {
            $this->throwValidationException($request->request(), $validator);
        }
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  RequestModel $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return void
     */
    public function validate(RequestModel $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        if ($request instanceof RequestModelCollection) {
            $request->map(function (RequestModel $requestModel) use ($rules) {
                $this->validate($requestModel, $rules);
            });
            return;
        }

        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException($request->request(), $validator);
        }
    }

    /**
     * Validate the given request data with the given rules.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return void
     */
    public function validateData(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException(request(), $validator);
        }
    }
    
    /**
     * Validate the given request with the given rules.
     *
     * @param  string $errorBag
     * @param  RequestModel $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return void
     *
     * @throws ValidationException
     */
    public function validateWithBag(
        $errorBag,
        RequestModel $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    )
    {
        $this->withErrorBag($errorBag, function () use ($request, $rules, $messages, $customAttributes) {
            $this->validate($request, $rules, $messages, $customAttributes);
        });
    }

    /**
     * Throw the failed validation exception.
     *
     * @param  Request $request
     * @param  \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator
     * @return void
     *
     * @throws ValidationException
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator, $this->buildFailedValidationResponse(
            $request, $this->formatValidationErrors($validator)
        ));
    }
}
