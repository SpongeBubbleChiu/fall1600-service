<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;

trait JsonApi
{
    protected function createJsonSerializeResponse($object, $groups = array()): Response
    {
        /** @var Serializer $serializer */
        $serializer = $this->get("serializer");
        $context = array("groups" => $groups);
        return $this->createJsonResponse($serializer->serialize($object, 'json', $context));
    }

    protected function createJsonResponse($data = null, $status = Response::HTTP_OK, $headers = array()): Response
    {
        $response = new JsonResponse(array(), $status, $headers);
        if (!is_string($data)) {
            $response->setData($data);
        } else {
            $response->setContent($data);
        }
        return $response;
    }
}
