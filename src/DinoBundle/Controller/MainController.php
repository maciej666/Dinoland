<?php

namespace DinoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AddUserDinoBundle\Entity\DinoParameters;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class MainController extends Controller
{
    /**
     * @Route("/", name="main")
     *
     * @Template
     */
    public function mainPageAction()
    {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $logged_user = $this->getUser();
        $dino_parameters = $logged_user->getDino();


        return array(
            'logged_user' => $logged_user,
            'dino_parameters' => $dino_parameters,
        );

    }

    /**
     * @Route("/dino_account", name="dino_account")
     * @return array
     * @Template
     */
    public function showDinoAction(){


        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $logged_user = $this->getUser();
        $dino_parameters = $logged_user->getDino();

        $updated = $this->get('dinoManager')->checkUpdate($logged_user);
        $dino_materials = $this->get('dinoManager')->showMaterials($logged_user);
        $dino_home = $this->get('dinoManager')->checkHomeRequirements($logged_user);
        $time_to_update = $this->get('dinoManager')->timeToUpdate($logged_user);
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('AddUserDinoBundle:DinoContent')->findAll();

        return array(
            'logged_user' => $logged_user,
            'dino_parameters' => $dino_parameters,
            'updated' => $updated,
            'dino_materials' => $dino_materials,
            'dino_home' => $dino_home,
            'content' => $content,
            'time_to_update' => $time_to_update
        );
    }






























    /**
     * @Route("/2", name="main2")
     *
     * @Template
     */
    public function mainPage2Action(){

        return array();

    }


    /**
     *
     * @Route("/message", name="message")
     *
     * @Template
     */
    public function messageAction()
    {
        return array();
    }

    /**
     * @Route("/sendMail", name="sendMail")
     */
    public function sendMailAction(){

        $subject = $_POST['subject'];
        $email = $_POST['email'];
        $body = $_POST['body'];
        $session = $this->get('session');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($email)
            ->setTo('boban.kamienczuk666@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'DinoBundle:Main:mail.html.twig',
                    array('body' => $body,
                        'subject' => $subject
                    )
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
        $session->getFlashBag()->add('success', 'Wysłano maila!');


        return $this->render('DinoBundle:Main:mainPage.html.twig');
    }

//    /**
//     * @Route("/test", name="test")
//     * @Template
//     */
//    public function testAction{
//        return array();
//    }
}
