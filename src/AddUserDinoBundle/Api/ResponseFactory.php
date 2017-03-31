<?php

namespace AddUserDinoBundle\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    public function createResponse(ApiProblem $apiProblem)
    {
        //type według Scrap'a to absolute url
        $data = $apiProblem->toArray();
        if($data['type'] != 'about:blank') {
            $data['type'] = 'http://dino.dev/app_test.php/errors#'.$data['type'];
        }

        $response = new  JsonResponse(
            $data,
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }
}