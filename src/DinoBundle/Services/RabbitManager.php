<?php

namespace DinoBundle\Services;

use Doctrine\ORM\EntityManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class RabbitManager implements ConsumerInterface {

    private $em;
    /**
     * @var FormBuilder
     */

    private $form;

    /**
     * @param EntityManager $em
     * @param FormFactory $form
     */
    public function __construct(EntityManager $em, FormFactory $form)
    {
        $this->em = $em;
        $this->form = $form;
    }


    public function execute(AMQPMessage $msg)
    {
        echo "Hello $msg->body!".PHP_EOL;
    }


}