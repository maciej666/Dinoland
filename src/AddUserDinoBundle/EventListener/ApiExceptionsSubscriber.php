<?php

namespace AddUserDinoBundle\EventListener;

use AddUserDinoBundle\Api\ApiProblem;
use AddUserDinoBundle\Api\ApiProblemException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiExceptionsSubscriber implements EventSubscriberInterface
{
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
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

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException',
        );
    }

}