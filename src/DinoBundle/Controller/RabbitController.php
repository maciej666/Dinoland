<?php

namespace DinoBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class RabbitController extends Controller
{
    /**
     * Wyświetla stronę główną
     * @Route("/rabbit/{name}", name="rabbit")
     * @Template
     */
    public function testAction($name) {

        $this->get('old_sound_rabbit_mq.dino_rabbitmq_producer')->publish($name);

        return array();

    }




}
