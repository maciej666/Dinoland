<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AddUserDinoBundle\Entity\User;
use AddUserDinoBundle\Form\DinoAdminType;
use AddUserDinoBundle\Form\DinoType;
use AddUserDinoBundle\Form\DinoMaterialsType;
use AddUserDinoBundle\Form\DinoParametersType;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
/**
 * Class PanelController
 * @package AdminBundle\Controller
 * @Route("/admin")
 */
class PanelController extends Controller
{
    /**
     * @Route("/index", name="index")
     * @Template
     */
    public function indexAction()
    {
        return array();
    }


    /**
     * Wyświetla tabele z userami
     * @Route("/users", name="users")
     * @Template
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AddUserDinoBundle:User')->findAll();

        //za pomocą render można zwrócić zawartość html'a
        $template = $this->render('AdminBundle:Panel:users.html.twig',array(
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
        $template = $this->render('AdminBundle:Panel:dashboard.html.twig')->getContent();
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
     * Sortuje po własnościach encji User, asc bądź desc
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function sortsAction(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:User');

        //rodzaj pola do sortowania np. id, email... wraz z info o asc bądź descl
        $sort = $request->request->get('sort');

        //rozbicie pola do sortowania i rodzaju sortowania
        $array = explode('.', $sort);
        $sort = $array[0];
        $sortType = $array[1];

        //jeżli przed sortowaniem user wyszukiwał coś to pobieram treść tego wyszukiwania
        //aby sortować tylko wyszukiwane pozycje
        $searchVal = $request->request->get('searchVal');

        //posortowani users'i
        $users = $repository->sortByField($searchVal, $sort, $sortType);

        //posortowana tabela
        $template = $this->render('AdminBundle:Panel:tabello.html.twig',array(
            'users' => $users,
            'edited' => false
        ))->getContent();
        $content = json_encode($template);

        $response = new JsonResponse();
        $response->setData(array(
            "code" => 100,
            "success" => true,
            "content" => $content
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }


    /**
     * Wyszukiwarka fraz przeszukująca encje User
     * @param Request $request
     */
    public function searchAction(Request $request){

        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:User');

        //wartość wpisanego tekstu
        $val = $request->request->get('search');

        //zaznaczone filtry, true or false
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $age = $request->request->get('age');
        $species = $request->request->get('species');

        $checked_filters = [];

        //dla wszystkich randomowo u.disabled - dziwne to tak
        //nie wiem jak zrobić inaczej
        for($i = 0; $i < 4; $i++){
            $checked_filters[$i] = 'u.enabled';
        }
        //tworzy tablice z filtrami
        if($email == 1){
            $checked_filters[0] = 'u.email';
        }
        if($name == 1){
            $checked_filters[1] = 'u.name';
        }
        if($age == 1){
            $checked_filters[2] = 'u.age';
        }
        if($species == 1){
            $checked_filters[3] = 'u.species';
        }

        $users = $repository->searchWords($val, $checked_filters);

        $template = $this->render('AdminBundle:Panel:tabello.html.twig',array(
            'users' => $users,
            "edited" => false //sprawdza czy był edytowany jakiś user patrz saveUserAction
        ))->getContent();
        $content = json_encode($template);

        $response = new JsonResponse();
        $response->setData(array(
            "code" => 200,
            "success" => true,
            "content" => $content,
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }


    /**
     * Wyświetla formularz do edycji usera
     * @return JsonResponse
     *
     */
    public function editUsersAction(Request $request){

        $repository = $this->getDoctrine()->getManager()->getRepository('AddUserDinoBundle:User');
        $id = $request->request->get('id');
//-----------Tworzenie formularza dla usera wraz z surowcami i statystykami-----------------
        $user = $repository->findUserById($id);
        $materials = $user->getMateria();
        $editForm = $this->createAdminForm($user, DinoAdminType::class, $id);

        //renderowanie widoku formularza
        $template = $this->render('AdminBundle:Panel:editUserForm.html.twig',array(
            'editForm' => $editForm->createView(),
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
     * @return JsonResponse
     * Zapisuje zmiany wprowadzone na obiekcie user
     */
    public function saveUsersAction(Request $request, $id)
    {
//        var_dump($request->getContent());die;
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AddUserDinoBundle:User');

        $user = $repository->find($id);
        $materials = $user->getMateria();
        $pass = $user->getPassword();

//        $encoder = new MessageDigestPasswordEncoder('sha1');
//        $password = $encoder->encodePassword($pass,  $user->getSalt());

//        var_dump($password);die;
        $editForm = $this->createAdminForm($user, DinoAdminType::class, $id);

//        $data = $request->request->get('data');
//        $editForm->setData($data);
//                        var_dump($editForm->get('plainPassword')->getData());die;
//        var_dump($editForm->handleRequest($request));die;
        $editForm->handleRequest($request);
//dump($editForm->handleRequest($request));die;



        //generowanie widoku tabello.html.twig i przekazanie doń obiektów users z
        //klasy User
        $users = $repository->findAll();

        $response = new JsonResponse();

        if ($editForm->isValid()) {

            $em->persist($user);
            $em->flush();

            $template = $this->render('AdminBundle:Panel:tabello.html.twig', array(
                'users' => $users,
                'user' => $user, //edytowany user
                'edited' => $id
            ))->getContent();
            $content = json_encode($template);

            $response->setData(array(
                "code" => 200,
                "success" => true,
                "content" => $content
            ));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        $template = $this->render('AdminBundle:Panel:editUserForm.html.twig', array(
            'users' => $users,
            'user' => $user, //edytowany user
            'edited' => $id,
            'editForm' => $editForm->createView(),
        ))->getContent();
        $content = json_encode($template);


        $response->setData(array(
            "code" => 400,
            "success" => false,
            "content" => $content
        ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    public function createAdminForm($entity, $form, $id=0){

        return $this->createForm($form, $entity, array(
            'action' => $this->generateUrl('Admin_save_user_change', ['id' => $id]),
            'method' => 'POST',
        ));
    }

}
