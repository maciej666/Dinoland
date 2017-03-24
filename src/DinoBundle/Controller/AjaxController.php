<?php

namespace DinoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AddUserDinoBundle\Entity\DinoParameters;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AjaxController extends Controller
{

    /**
     * Sprawdza czy upłynął czas po jakim user może przyznać punkty i jeśli można to zapisuje je do bazy danych
     *
     * Źródło jak działa ajax z symfony: http://stackoverflow.com/questions/13584591/how-to-integrate-ajax-with-symfony2
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $dinoParameters = $user->getDino();
        $dinoId = $dinoParameters->getId();
        $user_dino = $em->getRepository('AddUserDinoBundle:DinoParameters')->findOneById($dinoId);

        //sprawdza czy upłynał czas potrzebny do update'a
        $updated = $this->container->get('dinoManager')->checkUpdate($user);
        $left = $this->container->get('dinoManager')->timeToUpdate($user);

        //suma ptk. mocy z bazy danych
        $old_sum = $user_dino->getHealth() + $user_dino->getStrength() + $user_dino->getBackup() + $user_dino->getSpeed();

        $health = $request->request->get('health', '50');
        $speed = $request->request->get('speed', '50');
        $backup = $request->request->get('backup', '50');
        $strength = $request->request->get('strength', '50');

        //suma ptk. mocy z edytowanych pól za pomocą javascript
        $new_sum = $health + $speed + $backup + $strength;

        $diff = $new_sum - $old_sum;

        $response = new JsonResponse();
        //Sprawdz czy są dodawane dokładnie 2ptk i czy upłynał odpowiedni czas od ostatniej akcji.
        if ($updated && $diff == 2) {
            $dinoParameters->setStrength($strength);
            $dinoParameters->setSpeed($speed);
            $dinoParameters->setHealth($health);
            $dinoParameters->setBackup($backup);

            $new_update = new \DateTime('now');
            $last_update = $user_dino->setUpdatedAt($new_update);
            $em->persist($dinoParameters);
            $em->persist($last_update);
            $em->flush();

            $response->setData(array(
                "code" => 100,
                "success" => true,
                "left" => $left
            ));

            return $response;

        } else {
            $response->setData(array(
                "code" => 100,
                "success" => false
            ));

            return $response;
        }
    }


    /**
     * Funkcja która jest wykonywana co minutę i wysyła czas po którym można rozdać punkty
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateAjaxAction()
    {
        $user = $this->getUser();
        $updated = $this->container->get('dinoManager')->timeToUpdate($user);
        $response = new JsonResponse();
        //Jeśli null czas upłynał wyświetl guzik i liczbe punktów do rozdania
        if ($updated == null) {
            $response->setData(array(
                "code" => 100,
                "success" => true
            ));

            return $response;

        } else {
            $response->setData(array(
                "code" => 100,
                "success" => false,
                "data" => $updated
            ));

            return $response;

        }
    }


    /**
     * Funkcja która jest wykonywana co minutę i wysyła dane z encji DinoMaterials
     * zalogowanego usera
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function materialAjaxAction()
    {
        $logged_user = $this->getUser();
        $dino_materials = $this->container->get('dinoManager')->showMaterials($logged_user);

        $bone = $dino_materials->getBone();
        $wood = $dino_materials->getWood();
        $stone = $dino_materials->getStone();
        $flint = $dino_materials->getFlint();
        $access = $dino_materials->getAccess();

        $response = new JsonResponse();

        //Jeśli zwrócono obiekt
        if ($dino_materials) {
            $response->setData(array(
                "code" => 100,
                "success" => true,
                "bone" => $bone,
                "wood" => $wood,
                "stone" => $stone,
                "flint" => $flint,
                "access" => $access,
            ));

            return $response;

        } else {
            $response->setData(array(
                "code" => 100,
                "success" => false,
            ));

            return $response;

        }
    }


    /**
     * Buduje dinowi schronienie
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addHomeAjaxAction(Request $request)
    {
        $logged_user = $this->getUser();
        $dinoMaterialsId = $logged_user->getMateria()->getId();
        $em = $this->getDoctrine()->getManager();
        $dino_materials = $em->getRepository('AddUserDinoBundle:DinoMaterials')->findOneById($dinoMaterialsId);

        //Ilość surowca
        $bone = $dino_materials->getBone();
        $wood = $dino_materials->getWood();
        $stone = $dino_materials->getStone();
        $flint = $dino_materials->getFlint();

        //Informuje o tym jaki jest ostatni wykupiony poziom
        $access = $dino_materials->getAccess();

        //Sprawdza czy dino ma odpowiednią ilość surowców
        $requirements = [];
        $requirements = $this->container->get('dinoManager')->checkHomeRequirements($logged_user);

        //Wartość z guzika klikniętego przez usera
        $allowed = $request->request->get('access');

        //Jeśli jakimś cudem user przycisnął guzik którego nie powinien móc kliknąć
        if (!$allowed == $access) {
            throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
        }
        // W if switch'a sprawdza dla pewności czy user ma wystarczającą ilość surowca
        // w przypadku gdyby mógł nacisnąć przycisk bez wystarczającej ilości surowca
        // wywala go gdy można kliknąc pare razy ajax request o schronisko
        switch ($allowed) {
            case 0:
                if ($requirements[0] == 0) {
                    $dino_materials->setWood($wood-60);
                    $dino_materials->setStone($stone-40);
                    $dino_materials->setBone($bone-15);
                    $dino_materials->setFlint($flint-6);
                    $dino_materials->setAccess(1);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 1:
                if ($requirements[1] == 1 && $access == 1) {
                    $dino_materials->setWood($wood-120);
                    $dino_materials->setStone($stone-90);
                    $dino_materials->setBone($bone-40);
                    $dino_materials->setFlint($flint-25);
                    $dino_materials->setAccess(2);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 2:
                if ($requirements[2] == 2 && $access == 2) {
                    $dino_materials->setWood($wood-250);
                    $dino_materials->setStone($stone-170);
                    $dino_materials->setBone($bone-100);
                    $dino_materials->setFlint($flint-40);
                    $dino_materials->setAccess(3);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 3:
                if ($requirements[3] == 3 && $access == 3) {
                    $dino_materials->setWood($wood-340);
                    $dino_materials->setStone($stone-200);
                    $dino_materials->setBone($bone-130);
                    $dino_materials->setFlint($flint-70);
                    $dino_materials->setAccess(4);
                }else{
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 4:
                if ($requirements[4] == 4 && $access == 4) {
                    $dino_materials->setWood($wood-400);
                    $dino_materials->setStone($stone-320);
                    $dino_materials->setBone($bone-290);
                    $dino_materials->setFlint($flint-150);
                    $dino_materials->setAccess(5);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 5:
                if ($requirements[5] == 5 && $access == 5) {
                    $dino_materials->setWood($wood-560);
                    $dino_materials->setStone($stone-400);
                    $dino_materials->setBone($bone-310);
                    $dino_materials->setFlint($flint-170);
                    $dino_materials->setAccess(6);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 6:
                if ($requirements[6] == 6 && $access == 6) {
                    $dino_materials->setWood($wood-900);
                    $dino_materials->setStone($stone-790);
                    $dino_materials->setBone($bone-650);
                    $dino_materials->setFlint($flint-400);
                    $dino_materials->setAccess(7);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
            case 7:
                if ($requirements[7] == 7 && $access == 7) {
                    $dino_materials->setWood($wood-1100);
                    $dino_materials->setStone($stone-850);
                    $dino_materials->setBone($bone-740);
                    $dino_materials->setFlint($flint-500);
                    $dino_materials->setAccess(8);
                } else {
                    throw new NotFoundHttpException("Coś poszło nie tak odśwież stronę:)");
                }
                break;
        }

        $em->persist($dino_materials);
        $em->flush();

        $new_access = $dino_materials->getAccess();
        $new_requirements = $this->container->get('dinoManager')->checkHomeRequirements($logged_user);

        $response = new JsonResponse();

        $response->setData(array(
            "code" => 100,
            "success" => true,
            'allowed' => $allowed,
            'new_access' => $new_access,
            'new_requirements' => $new_requirements
        ));

        return $response;

    }
}


























