<?php

namespace Ipunkt\LaravelJsonApi\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Ipunkt\LaravelJsonApi\Contracts\RequestHandlers\ApiRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\ElementInterface;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * returns created response
     *
     * @param array|Document|ElementInterface $data
     * @return JsonResponse
     */
    public function respondCreated($data) : JsonResponse
    {
        return $this->respond($data, Response::HTTP_CREATED);
    }

    /**
     * responds no content
     *
     * @return JsonResponse
     */
    public function respondNoContent() : JsonResponse
    {
        return response()->json(null, 204)
            ->header('Content-Type', ApiRequestHandler::CONTENT_TYPE);
    }

    /**
     * respond json api
     *
     * @param array|Document|ElementInterface $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function respond($data, $status = Response::HTTP_OK, $headers = []) : JsonResponse
    {
        if ($data instanceof ElementInterface) {
            $data = new Document($data);
        }

        if ($data instanceof Document) {
            $data = $data->toArray();
        }

        return response()->json($data, $status, $headers)
            ->header('Content-Type', ApiRequestHandler::CONTENT_TYPE);
    }
}
