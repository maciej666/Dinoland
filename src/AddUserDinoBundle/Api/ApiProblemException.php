<?php

namespace AddUserDinoBundle\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Klasa nasłuchiwana przez ApiExceptionsSubscriber
 * odc. 11 course 2
 * Class ApiProblemException
 * @package AddUserDinoBundle\Api
 */
class ApiProblemException extends HttpException
{
    private $apiProblem;

    public function __construct(ApiProblem $apiProblem, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->apiProblem = $apiProblem;
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code); // TODO: Change the autogenerated stub
    }

    /**
     * @return ApiProblem
     */
    public function getApiProblem()
    {
        return $this->apiProblem;
    }

}