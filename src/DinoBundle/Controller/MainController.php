<?php

namespace DinoBundle\Controller;

use AddUserDinoBundle\Entity\DinoImage;
use AddUserDinoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AddUserDinoBundle\Entity\DinoParameters;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;



class MainController extends Controller
{
    /**
     * Wyświetla stronę główną
     * @Route("/", name="main")
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
     * Wyświetla stronę z kontem Dina
     * @Route("/dino_account", name="dino_account")
     * @return array
     * @Template
     */
    public function showDinoAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        /** @var User */
        $logged_user = $this->getUser();
        /** @var  DinoParameters */
        $dino_parameters = $logged_user->getDino();

        $dino_image = $this->get('dino_manager')->getImage($logged_user);
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $dino_image_path = $helper->asset($dino_image, 'imageFile');

        //Formularz do dodania zdjęcia Dina
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AddUserDinoBundle\Form\DinoImageType', $dino_image);

        $form->handleRequest($request);
//        var_dump($form->isValid());die;
        if ($form->isValid()) {
            $logged_user->setImage($dino_image);
            $em->persist($dino_image);
            $em->flush();
        }

        $updated = $this->get('dino_manager')->checkUpdate($logged_user);
        $dino_materials = $this->get('dino_manager')->showMaterials($logged_user);
        $dino_home = $this->get('dino_manager')->checkHomeRequirements($logged_user);
        $time_to_update = $this->get('dino_manager')->timeToUpdate($logged_user);
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('AddUserDinoBundle:DinoContent')->findAll();

        return array(
            'logged_user' => $logged_user,
            'dino_parameters' => $dino_parameters,
            'updated' => $updated,
            'dino_materials' => $dino_materials,
            'dino_home' => $dino_home,
            'content' => $content,
            'time_to_update' => $time_to_update,
            'upload_file_form' => isset($form) ? $form->createView() : NULL,
            'dino_image_path' => isset($dino_image_path) ? $dino_image_path : NULL
        );
    }








}
