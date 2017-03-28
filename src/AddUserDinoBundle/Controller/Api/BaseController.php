<?php

namespace AddUserDinoBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AddUserDinoBundle\Api\ApiProblemException;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AddUserDinoBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use AddUserDinoBundle\Api\ApiProblem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AddUserDinoBundle\Services;

abstract class BaseController extends Controller
{
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

        //sprawdzanie czy dodano jakąś grupę serializati
        $request = $this->get('request_stack')->getCurrentRequest();
        $groups = array('Default'); // Standardowa grupa
        //sprawdza czy dodano do requesta grupe
        if ($request->query->get('deep')) {
            $groups[] = 'deep';
        }
        $context->setGroups($groups);

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