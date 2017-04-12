<?php

namespace AdminBundle\Controller;

use AddUserDinoBundle\Entity\DinoMaterials;
use AddUserDinoBundle\Entity\DinoParameters;
use AddUserDinoBundle\Form\DinoMaterialsType;
use AddUserDinoBundle\Form\DinoParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AddUserDinoBundle\Entity\User;
use AddUserDinoBundle\Form\DinoAdminType;

/**
 * Class PanelController
 * @package AdminBundle\Controller
 * @Route("/newadmin")
 */
class NewPanelController extends Controller
{
    /**
     * @Route("/index", name="indexnew")
     * @Template
     */
    public function indexAction()
    {
        return array();
    }


    /**
     * Wyświetla tabele z userami
     * @Route("/users", name="AdminShowUsersNew")
     * @Template
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AddUserDinoBundle:User')->findAll();

        //za pomocą render można zwrócić zawartość html'a
        $template = $this->render('AdminBundle:NewPanel:users.html.twig',array(
            'users' => $users,
            "edited" => false//tymczasowe średnie rozwiązanie, sprawdza czy był edytowany jakiś user patrz saveUserAction
        ))->getContent();
        $content = json_encode($template);
        $response = new JsonResponse();
        $response->setData(array(
            "code" => 100, //200 z jakiegoś powodu nie działa??
            "success" => true,
            "content" => $content,
        ));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * Wyświetla zakładke dashboard panelu admina
     * @return JsonResponse
     * @throws \Exception
     */
    public function daschboardAction()
    {
        $template = $this->render('AdminBundle:NewPanel:dashboard.html.twig')->getContent();
        $content = json_encode($template);

        $response = new JsonResponse();
        $response->setData(array(
            "code" => 100, //200 z jakiegoś powodu nie działa??
            "success" => true,
            "content" => $content
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }


    /**
     * Wyświetla formularz do edycji usera
     * @return JsonResponse
     * AdminEditUserNew
     */
    public function editUsersAction(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:User');
        $id = $request->request->get('id');
//-----------Tworzenie formularza dla usera wraz z surowcami i statystykami-----------------
        /** @var User */
        $user = $repository->findUserById($id);
        /** @var DinoMaterials */
        $materials = $user->getMateria();
        /** @var DinoParameters */
        $parameters = $user->getDino();

        $editUserForm = $this->createAdminForm($user, DinoAdminType::class, $id);
        $editParametersForm = $this->createAdminForm($parameters, DinoParametersType::class, $id);
        $editMaterialsForm = $this->createAdminForm($materials, DinoMaterialsType::class, $id);

        //renderowanie widoku formularza
        $template = $this->render('AdminBundle:NewPanel:editUserForm.html.twig',array(
            'editUserForm' => $editUserForm->createView(),
            'editParametersForm' => $editParametersForm->createView(),
            'editMaterialsForm' => $editMaterialsForm->createView(),
            'user' => $user
        ))->getContent();
        $content = json_encode($template);

        $response = new JsonResponse();
        $response->setData(array(
            "code" => 200,
            "success" => true,
            "content" => $content
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * Zapisuje zmiany wprowadzone na obiekcie user
     * @return JsonResponse
     */
    public function saveUsersAction(Request $request, $id, $formType)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AddUserDinoBundle:User');

        $id = $request->get('id');
        /** @var  User */
        $user = $repository->findUserById($id);
        /** @var  User */
        $users = $repository->findAll();

        $formType = $request->get('formType');
        /** return form (User | Parameters | Materials) */
        $editForm = $this->container->get('dino_admin_manager')->saveAdminForm($request, $user, $formType);

        if ($editForm == false) {
            throw new \Exception('Przesłano niedozwolony parametr');
        }

        //Wyświeta strone z info o udanym zapisie bądź z info o błędach
        if ($editForm[0]->isValid()) {
            $template = $this->render('AdminBundle:NewPanel:tabello.html.twig', array(
                'users' => $users,
                'user' => $user, //edytowany user
                'edited' => $id
            ))->getContent();
            $content = json_encode($template);

            $response = new JsonResponse();
            $response->setData(array(
                "code" => 200,
                "success" => true,
                "content" => $content
            ));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        } else {
        $forms = [];
        /** Return array of forms */
        $forms = $this->container->get('dino_admin_manager')->checkEditedForm($user, $editForm);

        //Gdy formularz nie przechodzi walidacji wyświetlany jest ponownie wraz z błędami
        $template = $this->render('AdminBundle:NewPanel:editUserForm.html.twig', array(
            'users' => $users,
            'user' => $user, //edytowany user
            'edited' => $id,
            'editUserForm' => $forms[0]->createView(),
            'editParametersForm' => $forms[1]->createView(),
            'editMaterialsForm' => $forms[2]->createView(),
            'activeInfo' => $forms[3] //info potrzebne aby wyświetlić odpowiednią zakładkę w jQuery accordion
        ))->getContent();
        $content = json_encode($template);

        $response = new JsonResponse();
        $response->setData(array(
            "code" => 400,
            "success" => false,
            "content" => $content
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
        }
    }


    /**
     * Tworzenie akcji na podstawie encji, odpowiedniego formularza i id edytowanego usera
     * @param $entity
     * @param $form
     * @param int $id
     * @return \Symfony\Component\Form\Form
     */
    public function createAdminForm($entity, $form, $id=0){

        return $this->createForm($form, $entity, array(
            'action' => $this->generateUrl('Admin_save_user_change', ['id' => $id]),
            'method' => 'POST',
        ));
    }

}
