<?php

namespace AddUserDinoBundle\Controller\Api;

use AddUserDinoBundle\Entity\DinoParameters;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AddUserDinoBundle\Form\DinoParametersType;
use AddUserDinoBundle\Form\DinoType;
use AddUserDinoBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AddUserDinoBundle\Services;

/**
 * Class DinoApiController
 * @package AddUserDinoBundle\Controller\Api
 * @Security("is_granted('ROLE_USER')")
 */
class DinoApiController extends BaseController
{
    /**
     * Tworzy Usera w oparciu o przekazane dane
     * @Route("api/dino")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $user = new User();

        $body = $request->getContent();
        $data = json_decode($body, true); //array

        $pass = $this->container->get('security.password_encoder')
            ->encodePassword($user, $data['plainPassword']);
        $user->setPassword($pass); //mało bezpieczne, boć user przesyła niezaszyfrowane hasło
        $user->setEnabled(true); //ustawiam true gdyż nie ma aktywacji za pomocą confirmation_token
//        var_dump($pass);die;
        $form = $this->createForm(DinoType::class, $user, array(
            'csrf_protection' => false
        ));

        $this->processForm($request, $form);

        if(!$form->isValid()){
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $location = $this->generateUrl('api_show_dino',[
            'email' => 'dalina'
        ]);
        $response = $this->createApiResponse($user, 201);
        $response->headers->set('Location', $location); //przekierowanie

        return $response;
    }


    /**
     * Przekazuje usera po mailu
     * @Route("api/dino/{email}", name="api_show_dino")
     * @Method("GET")
     */
    public function showAction($email)
    {
        $dino = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail($email);
//        var_dump($dino->getDino());die;

        if(!$dino) {
            throw $this->createNotFoundException('Nie ma takiego Dina '.$email);
        }

        $response = $this->createApiResponse($dino);

        return $response;
    }


    /**
     * Przekazuje wszystkich userów
     * Korzysta z WhiteOctoberPagerfantaBundle, odc. 2 course 3
     * @Route("api/dino", name="api_users_collection")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $filter = $request->query->get('filter');

        $qb = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findAllQueryBuilder($filter);

        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_users_collection');

        $response = $this->createApiResponse($paginatedCollection);

        return $response;
    }


    /**
     * Aktualizuje usera po mailu
     * @Route("api/dino/{email}", name="api_update_dino")
     * @Method({"PUT", "PATCH"})
     */
    public function updateAction($email, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail($email);
        if(!$user){
            throw $this->createNotFoundException('Nie ma takiego Usera: '.$user);
        }

        $form = $this->createForm(DinoType::class, $user, [
            'is_edit' => true, //REST course 1 odc. 15 - zablokowanie edycji właściwości. Tylko czemu usuwa właściwość której nie można edytowac?
            'csrf_protection' => false
        ]);
        $this->processForm($request, $form);

        if(!$form->isValid()){
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $response = $this->createApiResponse($user);//przekazanie stworzonych parametrów

        return $response;
    }


    /**
     * Usuwa usera po mailu
     * @Route("api/dino/{email}", name="api_delete_dino")
     * @Method("DELETE")
     */
    public function deleteAction($email)
    {
        $user = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail($email);
        if($user){
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return new JsonResponse(null, 204); //zawsze zwracamy 204, boć jak nie ma usera'a to i tak wynik jest ten sam. Jest to idempotentne.
    }


    /**
     * Tworzy obiekt DinoParameters w oparciu o przekazane dane
     * @Route("api/dino/parameters")
     * @Method("POST")
     */
    public function createParametersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $parameters = new DinoParameters();
        $parameters->setCreatedAt(new \DateTime());

        //przypisuje parametry do usera
        $user = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail('ApiMail@ty.pl');
        $parameters->setUser($user);

        $form = $this->createForm(DinoParametersType::class, $parameters, array(
            'csrf_protection' => false
        ));
        $this->processForm($request, $form);

        if(!$form->isValid()){
//            header('Content-Type: cli');//co by łądnie błąd wywalić w konsolecie:)
//            dump((string) $form->getErrors(true, false ));die;
            $this->throwApiProblemValidationException($form);
        }

        $em->persist($parameters);
        $em->flush();

        $location = $this->generateUrl('api_show_dino',[
            'email' => 'dalina'
        ]);
        $response = $this->createApiResponse($parameters, 201);
        $response->headers->set('Location', $location); //przekierowanie

        return $response;
    }




}









