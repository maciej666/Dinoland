<?php

namespace AddUserDinoBundle\Serializer;

use AddUserDinoBundle\Annotation\Link;
use AddUserDinoBundle\Entity\User;
use Doctrine\Common\Annotations\Reader;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Routing\RouterInterface;

/**
 * Gdy chcemy skorzystać z service'u w encji możemy użyć JMS'owego subscribera
 * Dodaje nowe pole uri do każdego nowo serializowanego obiektu User
 * Odc. 6 course 3
 * Class LinkSerializationSubscriber
 * @package AddUserDinoBundle\Serializer
 */
class LinkSerializationSubscriber implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;


    /**
     * Pozwala odczytać stworzone przez nas adnotacje
     * @var Reader
     */
    private $annotationReader;


    private $expressionLanguage;


    public function __construct(RouterInterface $router, Reader $annotationReader )
    {

        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->expressionLanguage = new ExpressionLanguage(); //pozwala przetworzyć kod z adnotacji Link
    }


    /**
     * Dodaje pole uri do encji User wraz ze ścieżką do własnego zasobu
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();//obsługuje serializacje

        $object = $event->getObject(); // aktualnie serializowany obiekt

        $annotations = $this->annotationReader
            ->getClassAnnotations(new \ReflectionObject($object));
        $links = array();

        //odpalamy tylko gdy klasa ma adnotacje link
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Link) {
                $uri = $this->router->generate(
                    $annotation->route,
                    $this->resolveParams($annotation->params, $object)
                );
                $links[$annotation->name] = $uri;
            }
        }

        if ($links) {
            $visitor->addData('_links', $links); //tak o gdyż przy paginacji dodajemy pole _links a pola nie mogą sie duplować w JMS, odc. 9 course 3
        }
    }


    private function resolveParams(array $params, $object)
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->expressionLanguage
                ->evaluate($param, array('object' => $object));
        }

        return $params;
    }


    public static function getSubscribedEvents()
    {
        return array(
        array (
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerialize',
                'format' => 'json', // event zostanie odpalony tylko jeżeli serailizujemy do  json'a
//                'class' => 'AddUserDinoBundle\Entity\User' // odpalaj tylko przy tej klasie
            ));
    }


}