<?php

namespace AddUserDinoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AddUserDinoBundle\Entity\DinoParameters;
use AddUserDinoBundle\Entity\User;
use AddUserDinoBundle\Form\DinoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Klasa do testowania rozwiązań. Do usunięcia
 * Class UserDinoController
 * @package AddUserDinoBundle\Controller
 */
class UserDinoController extends Controller
{
    /**
     * @return array
     * @Route ("/test", name="test")
     * @Template
     */
    public function test(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dinoParameters = new DinoParameters();

        $form = $this->createFormBuilder($dinoParameters)
            ->add('backup', TextType::class)
            ->add('health', TextType::class)
            ->add('strength', TextType::class)
            ->add('speed', TextType::class)
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save'),
            ))
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($dinoParameters);
            $em->flush();

            $dispatcher = new EventDispatcher();
            $listener = new TestListener();
            $dispatcher->addListener('acme.action', array($listener, 'onFooAction'));

            $this->redirect($this->generateUrl('test'));
        }

        return array(
            'form' => isset($form) ? $form->createView() : NULL
        );
    }


    /**
     * @return array
     * @Route ("/test2", name="test2")
     * @Template
     */
    function test2Action()
    {
        return array();
    }
}









