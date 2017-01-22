<?php

namespace Ipunkt\LaravelJsonApi\Testing;

use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler;

trait ApiTestCaseTrait
{
    /** --- Authentication Testing --- **/

    /**
     * authentication token
     *
     * @var string|null
     */
    protected static $token;

    /**
     * sets token
     *
     * @param string|null $token
     * @return ApiTestCaseTrait
     */
    protected function setToken(string $token = null): self
    {
        static::$token = $token;

        return $this;
    }

    /**
     * logout the user
     *
     * @return ApiTestCaseTrait
     */
    protected function logout(): self
    {
        $this->setToken(null);

        return $this;
    }

    /** --- Prepare Request --- **/

    /**
     * creates a request model like json api client
     *
     * @param string $type
     * @param array $data
     * @param null|string|integer $id
     * @return array
     */
    protected function createRequestModel(string $type, array $data = [], $id = null)
    {
        return [
            'data' => [
                'id' => $id,
                'type' => $type,
                'attributes' => $data
            ],
            'jsonapi' => [
                'version' => '1.0'
            ],
            'meta' => [
                'generator' => 'JsonApiTester'
            ]
        ];
    }

    /**
     * creates a request model like json api client with multiple ids
     *
     * @param string $type
     * @param array|string $ids
     * @return array
     */
    protected function createRequestModelArray(string $type, $ids = [])
    {
        if (!is_array($ids)) {
            $ids = (array)$ids;
        }

        $data = [];
        foreach ($ids as $id) {
            $data[] = [
                'type' => $type,
                'id' => $id,
            ];
        }

        return [
            'data' => $data,
            'jsonapi' => [
                'version' => '1.0'
            ],
            'meta' => [
                'generator' => 'JsonApiTester'
            ]
        ];
    }

    /**
     * returns headers
     *
     * @return array
     */
    protected function headers(): array
    {
        $headers = [
            'Accept' => ApiRequestHandler::CONTENT_TYPE,
            'Content-Type' => ApiRequestHandler::CONTENT_TYPE,
        ];

        if (!empty(static::$token)) {
            $headers['Authorization'] = 'Bearer ' . static::$token;
        }

        return $headers;
    }

    /** --- Executing Request --- **/

    /**
     * makes api GET request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     * @return ApiTestCaseTrait
     */
    protected function api(string $uri, array $requestModel = [], array $headers = []): self
    {
        return $this->json('GET', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /**
     * makes api POST request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     * @return ApiTestCaseTrait
     */
    protected function apiPost(string $uri, array $requestModel = [], array $headers = []): self
    {
        return $this->json('POST', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /**
     * makes api PATCH request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     * @return ApiTestCaseTrait
     */
    protected function apiPatch(string $uri, array $requestModel = [], array $headers = []): self
    {
        return $this->json('PATCH', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /**
     * makes api DELETE request
     *
     * @param string $uri
     * @param array $requestModel
     * @param array $headers
     * @return ApiTestCaseTrait
     */
    protected function apiDelete(string $uri, array $requestModel = [], array $headers = []): self
    {
        return $this->json('DELETE', $uri, $requestModel, array_merge($this->headers(), $headers));
    }

    /** --- Process Response --- **/

    /**
     * assert error response with status code and message context
     *
     * @param int $statusCode
     * @param array|null $structure
     */
    protected function assertErrorResponse(int $statusCode, array $structure = null)
    {
        $this->assertResponseStatus($statusCode);
        $this->seeJsonStructure($structure ?? [
                'errors' => [
                    '*' => [
                        $statusCode => 'status',
                        'title'
                    ]
                ]
            ]);
    }

    /**
     * returns response id
     *
     * @return string
     */
    protected function getResponseId(): string
    {
        $json = $this->decodeResponseJson();

        return array_get($json, 'data.id');
    }

    /**
     * returns response type
     *
     * @return string
     */
    protected function getResponseType(): string
    {
        $json = $this->decodeResponseJson();

        return array_get($json, 'data.type');
    }
}
