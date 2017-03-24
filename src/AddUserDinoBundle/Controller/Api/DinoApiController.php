<?php

namespace AddUserDinoBundle\Controller\Api;

use AddUserDinoBundle\Api\ApiProblemException;
use AddUserDinoBundle\Entity\DinoParameters;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AddUserDinoBundle\Form\DinoParametersType;
use AddUserDinoBundle\Form\DinoType;
use AddUserDinoBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use AddUserDinoBundle\Api\ApiProblem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AddUserDinoBundle\Services;

class DinoApiController extends Controller
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
            'dino' => 'dalina'
        ]);
        $response = $this->createApiResponse($user, 201);
        $response->headers->set('Location', $location); //przekierowanie

        return $response;
    }


    /**
     * Przekazuje usera po mailu
     * @Route("api/dino/{dino}", name="api_show_dino")
     * @Method("GET")
     */
    public function showAction($dino)
    {
        $dino = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findOneByEmail($dino);

        if(!$dino) {
            throw $this->createNotFoundException('Nie ma takiego Dina '.$dino);
        }
        $response = $this->createApiResponse($dino);

        return $response;
    }


    /**
     * Przekazuje wszystkich userów
     * @Route("api/dino")
     * @Method("GET")
     */
    public function listAction()
    {
        $dinos = $this->getDoctrine()
            ->getRepository('AddUserDinoBundle:User')
            ->findAll();

        $data = ['users' => $dinos];
        $response = $this->createApiResponse($data);

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
            'dino' => 'dalina'
        ]);
        $response = $this->createApiResponse($parameters, 201);
        $response->headers->set('Location', $location); //przekierowanie

        return $response;
    }


    /**
     * Metoda pobiera dane z Requesta i zapisuje je przy pomocy formularza
     * @param Request $request
     * @param $form
     */
    public function processForm(Request $request, $form)
    {
        $body = $request->getContent();
        //Decodowanie do array'a; true sprawia że otrzymamy array nie object
        $data = json_decode($body, true);
        //jeżeli przesłaliśmy źle sforamtowanego json'a, json_decode zwraca null
        //wyrzucamy błąd odc. 8,9 course 2
        if($data === null){
            $apiProblem = new ApiProblem(
                400,
                ApiProblem::INVALID_REQUEST_BODY_FORMAT
                );
            //aby przerwać działanie w metodzie proccessForm nie można dać poprostu return'a ?!?!
            //patrz: AddUserDinoBundle/Api/ApiProblemException
            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH'; //potrzebne gdyż używamy disabled w formType. Bez tego gdy używammy PATCH'a do update'a wstawia null w pola których nie chcemy zaktualizować.
        $form->submit($data, $clearMissing); //submit to funkcja która jest wywoływana w handleRequest
    }


    public function createApiResponse($data, $statusCode = 200)
    {
        $json = $this->serialize($data);

        return new Response($json, $statusCode, [
           'Content-Type' => 'application/json'
        ]);
    }


    /**
     * Korzysta z JMSSerializerBundle
     * @param User $dino
     * @return array
     */
    private function serialize($data)
    {
        //Kiedy wartość zwracanego pola to null, serializer nie uwzględnia go wogóle i nie przesyła np. 'health' => null. Stąd poniższe linie. Patrz odc. 20 course 1.
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->container->get('jms_serializer')
            ->serialize($data, 'json', $context);
    }


    /**
     * Zwraca błedy jakie się pojawiły podczas walidacji
     * Knp University odc. 1-2? course 2
     * @param FormInterface $form
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }


    /**
     * Zwraca JsonResponse wraz z błędami
     * @param FormInterface $form
     * @return JsonResponse
     */
    public function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);

        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_DINO_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }








}









