<?php

namespace Ipunkt\LaravelJsonApi\Testing\Concerns;

trait PreparesRequestBody
{
    /**
     * @var string
     */
    protected static $jsonApiVersion = '1.0';

    /**
     * @var string
     */
    protected static $generatorStatement = 'JsonApiTester';

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
                'version' => static::$jsonApiVersion
            ],
            'meta' => [
                'generator' => static::$generatorStatement
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
                'version' => static::$jsonApiVersion
            ],
            'meta' => [
                'generator' => static::$generatorStatement
            ]
        ];
    }
}