<?php

namespace AddUserDinoBundle\Controller;

use AddUserDinoBundle\Entity\Blog\Comment;
use AddUserDinoBundle\Entity\Blog\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirect('login');
        }

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AddUserDinoBundle:Blog\Comment');

        $form = $this->createForm('AddUserDinoBundle\Form\Blog\CommentType');
        $htmlTree = $repo->childrenHierarchy();
//        dump($htmlTree);die;
        return array(
            'htmltree' => $htmlTree,
            'form' => $form->createView()
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


    /**
     * @return array
     * @Route ("/payu", name="payu")
     * @Template
     */
    function testPayUAction() {
        return array();
    }
}









