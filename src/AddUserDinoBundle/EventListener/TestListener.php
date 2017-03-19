<?php

use Symfony\Component\EventDispatcher\Event;
use AddUserDinoBundle\Entity\Test;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * TESTY
 * Class AcmeListener
 */
class AcmeListener
{

public function onFooAction(Event $event)
{
    $test = new Test();

    $test->setName('Boban');
    $test->setAge(11);

    $em = $this->getDoctrine()->getManager();
    $em->persist($test);
    $em->flush();
}
}