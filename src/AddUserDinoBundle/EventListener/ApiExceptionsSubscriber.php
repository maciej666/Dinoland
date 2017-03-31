<?php

namespace AddUserDinoBundle\EventListener;

use AddUserDinoBundle\Api\ApiProblem;
use AddUserDinoBundle\Api\ApiProblemException;
use AddUserDinoBundle\Api\ResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Łapie wszystkie exceptions i zamienia je w ApiProblemExceptions
 * Class ApiExceptionsSubscriber
 * @package AddUserDinoBundle\EventListener
 */
class ApiExceptionsSubscriber implements EventSubscriberInterface
{
    private $debug;


    /**
     * AddUserDinoBundle/Api/ResponseFactory
     * @var ResponseFactory
     */
    private $responseFactory;


    public function __construct($debug, ResponseFactory $responseFactory)
    {
        $this->debug = $debug;
        $this->responseFactory = $responseFactory;
    }


    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        //sprawdzanie czy request dotyczy api
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), '/api') !== 0) {
            return;
        }

        $e = $event->getException();
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        //wyłączenie ApiExceptionsSubscriber w trybie debug, jeżeli wyskoczy 'nieznany błąd' chcemy  aby pokazywało ładne błędy
        if ($statusCode == 500 && $this->debug) {
            return;
        }

        if($e instanceof ApiProblemException) {
            $apiProblem = $e->getApiProblem();
        } else {
            //odc. 12 course 2 obsługuje np. 404-Not Found, czy 403-Forbidden
            //HttpExceptionInterface posiada parę standardowych wyjątków
            $apiProblem = new ApiProblem($statusCode);

            if($e instanceof HttpExceptionInterface) { // tylko z klasy HttpExceptionInterface, gdyż nie chcemy pokazywać wszystkich błędów użytkownikowi.
                $apiProblem->set('detail', $e->getMessage()); //detail to dodatkowe pole z info dla użytkownika strony,
            }
        }

        $response = $this->responseFactory->createResponse($apiProblem);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException',
        );
    }

}