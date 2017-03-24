<?php

namespace AddUserDinoBundle\Api;

use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 * Created by knpUniversity odc. 5 course 2
 * Według standardu Spec(?) o zwracaniu błędów. Stąd ta klasa aby wszystkie błedy były syntaktycznie spójne.
*/
class ApiProblem
{
    //wprowadzone stałe aby konkretny type miał zawsze taki sam title
    const TYPE_DINO_VALIDATION_ERROR = 'dino_validation_error';
    const INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';

    private static $titles = array(
        self::TYPE_DINO_VALIDATION_ERROR => 'Wprowadzono niepoprawne dane',
        self::INVALID_REQUEST_BODY_FORMAT => 'Wysłano nieprawidłowy format Json'
    );

    private $statusCode;

    private $type; //absolute uri

    private $title;

    private $extraData = array();

    public function __construct($statusCode, $type = null)
    {
        $this->statusCode = $statusCode;

        if ($type === null) {
            //about:blank ustawiamy gdy kod mówi sam za siebie według 'Spec-standard'
            $type = 'about:blank'; // 404
            //opis na podstawie kodu z klasy Response(tej HTTPFoundation)
            $title = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown status code :(';
        } else {
            //gdy źle wpisaliśmy type
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for '.$type);
            }
            //pobieramy tytuł na podstawie stałej
            $title = self::$titles[$type];
        }
        $this->type = $type;
        $this->title = $title;
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }


    public function toArray()
    {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            )
        );
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }



}