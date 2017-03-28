<?php

namespace AddUserDinoBundle\Annotation;


use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 * Dodawanie własnych adnotacji; patrz odc. 8 course 3
 * Class Link
 * @package AddUserDinoBundle\Annotation
 */
class Link
{
    /**
     * @Required
     */
    public $name;

    /**
     * @Required
     */
    public $route;

    public $params = array();
}