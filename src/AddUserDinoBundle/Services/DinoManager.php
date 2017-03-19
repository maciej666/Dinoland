<?php

namespace AddUserDinoBundle\Services;

use Doctrine\ORM\EntityManager;
use AddUserDinoBundle\Entity\DinoParameters;
use AddUserDinoBundle\Entity\DinoMaterials;
use AddUserDinoBundle\Entity\User;

/**
 * Class DinoManager
 * @package AddUserDinoBundle\Services
 */
class DinoManager
{
    private $em;

    /**
     * @param EntityManager         $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * Tworzy obiekt DinoParameters i DinoMaterials, przypisuje go do user'a i zapisuje do bazy danych
     *
     * @param User $loggedUser
     *
     * @return bool
     */
    public function setDinoParametersAndMaterials(User $loggedUser)
    {
        $dinoParameters = new DinoParameters();
        $dinoMaterials = new DinoMaterials();

        $loggedUserDinoAge = $loggedUser->getAge();

        $dinoParameters->setStrength(8+2*$loggedUserDinoAge);
        $dinoParameters->setSpeed(102-2*$loggedUserDinoAge);
        $dinoParameters->setHealth(69+$loggedUserDinoAge);
        $dinoParameters->setBackup(101-$loggedUserDinoAge);
        $dinoMaterials->setStone(480);
        $dinoMaterials->setWood(620);
        $dinoMaterials->setBone(290);
        $dinoMaterials->setFlint(170);
        $dinoMaterials->setAccess(0);

        $date = new \DateTime('now');
        $dinoParameters->setCreatedAt($date);
        $dinoMaterials->setCreatedAt($date);
        $dinoParameters->setUpdatedAt($date);
        $dinoMaterials->setUpdatedAt($date);

        $loggedUser->setDino($dinoParameters);
        $loggedUser->setMateria($dinoMaterials);
        $this->em->persist($dinoParameters);
        $this->em->persist($dinoMaterials);
        $this->em->flush();

        return true;
    }


    /**
     * Pobiera właściwość update z DinoParameters Entity i sprawdz czy upłynał zadany odcinek czasu
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkUpdate(User $user)
    {
        $dinoParameters = $user->getDino();
        $dinoId = $dinoParameters->getId();
        $user_dino = $this->em->getRepository('AddUserDinoBundle:DinoParameters')->findOneById($dinoId);
        $last_update = $user_dino->getUpdatedAt();

        $date1 = new \DateTime('now');
        $date11 = new \DateTime('now');
        $date2 = new \DateTime('+86400 seconds'); //1 dzień

        //ile czasu upłyneło od update'u
        $interval = $last_update->diff($date1);

        //To tworzy obiekt DateInterval z przedziałem 1 dnia
        $interval2 = $date1->diff($date2);

        //To tworzy obiekty DateTime + odpowiedni przedział czasowy (tak o gdyż nie mozna porównywac obiektów DateInterval)
        $date1->add($interval);
        $date11->add($interval2);

        //Sprawdza czy upłynął 1 dzień
        if($date1 > $date11 ){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Sprawdza czy upłynał czas po którym można dodać punkty mocy, jeżeli tak
     * zwraca null, jeżeli nie zwraca liczbę minut jaka pozostała
     *
     * @param User $user
     * @return null || $time
     */
    public function timeToUpdate(User $user)
    {
        $dinoParameters = $user->getDino();
        $dinoId = $dinoParameters->getId();
        $user_dino = $this->em->getRepository('AddUserDinoBundle:DinoParameters')->findOneById($dinoId);
        $last_update = $user_dino->getUpdatedAt();

        $date1 = new \DateTime('now');
        $date11 = new \DateTime('now');
        $date2 = new \DateTime('+86400 seconds'); //1 dzień

        //ile czasu upłyneło od update'a
        $interval = $last_update->diff($date1);

        //To tworzy obiekt DateInterval z przedziałem 1 dnia
        $interval2 = $date1->diff($date2);

        //To tworzy obiekty DateTime +  czas jaki upłynał od update'a (tak o gdyż nie mozna porównywac obiektów DateInterval)
        $date1->add($interval);
        //Obiekt DateTime + 1 dzień
        $date11->add($interval2);

        //ilość sekund do kolejnego przydziału mocy
        $unix_time_pass = 0;
        $unix_time_pass += $interval->h * 3600;
        $unix_time_pass += $interval->i * 60;
        $unix_time_pass += $interval->s;
        $unix_time_left = 86400 - $unix_time_pass;

        if($date11 < $date1){

            return null;
        }else{

            return $unix_time_left;
        }
    }


    /**
     * Odświeża ilość materiałów encji DinoMaterials
     * @param User $user
     * @return mixed
     */
    public function showMaterials(User $user){

        $dinoMaterialsId = $user->getMateria()->getId();
        $dino_materia = $this->em->getRepository('AddUserDinoBundle:DinoMaterials')->findOneById($dinoMaterialsId);
        $old_time_materials = $dino_materia->getUpdatedAt();

        $date = new \DateTime('now');

        $unix_old = $old_time_materials->format('U');
        $unix_new = $date->format('U');

        //ilość sekund jaka upłyneła od ostatniego zapisu
        $difference = $unix_new - $unix_old;

        //ilości surowców
        $wood = $dino_materia->getWood();
        $bone = $dino_materia->getBone();
        $stone = $dino_materia->getStone();
        $flint = $dino_materia->getFlint();
        $access = $dino_materia->getAccess();

//        sprawdzenie bonusów w zależności od poziomu schronienia
        $wood_bonus = 1;
        $bone_bonus = 1;
        $stone_bonus = 1;
        $flint_bonus = 1;

        switch($access){
            case 1:
                $wood_bonus = 1.05;
                break;
            case 2:
                $wood_bonus = 1.05;
                $stone_bonus = 1.04;
                $bone_bonus = 1.02;
                break;
            case 3:
                $wood_bonus = 1.05 * 1.15;
                $stone_bonus = 1.04;
                $bone_bonus = 1.02;
                break;
            case 4:
                $wood_bonus = 1.05 * 1.15;
                $stone_bonus = 1.04 * 1.10;
                $bone_bonus = 1.02 * 1.10;
                break;
            case 5:
                $wood_bonus = 1.05 * 1.15;
                $stone_bonus = 1.04 * 1.10;
                $bone_bonus = 1.02 * 1.10;
                $flint_bonus = 1.07;
                break;
            case 6:
                $wood_bonus = 1.05 * 1.15 * 1.10;
                $stone_bonus = 1.04 * 1.10 * 1.10;
                $bone_bonus = 1.02 * 1.10 * 1.10;
                $flint_bonus = 1.07;
                break;
            case 7:
                $wood_bonus = 1.05 * 1.15 * 1.10 * 1.40;
                $stone_bonus = 1.04 * 1.10 * 1.10;
                $bone_bonus = 1.02 * 1.10 * 1.10;
                $flint_bonus = 1.07;
                break;
            case 8:
                $wood_bonus = 1.05 * 1.15 * 1.10 * 1.40 * 1.20;
                $stone_bonus = 1.04 * 1.10 * 1.10 * 1.20;
                $bone_bonus = 1.02 * 1.10 * 1.10 * 1.20;
                $flint_bonus = 1.07 * 1.20;
                break;
        }

        //reszta sekund (z poprzedniego liczenia) nie przeliczona na ptk.
        $time_wood = $dino_materia->getWoodExtra();
        $time_stone = $dino_materia->getStoneExtra();
        $time_bone = $dino_materia->getBoneExtra();
        $time_flint = $dino_materia->getFlintExtra();

        //czas jaki upłynał od ostatniego zapsiu plus reszta z dzielenia
        //ostatniego wyznaczania ptk. do przyznania
        //plus bonus za schron o ile taki jest
        $new_time_wood = ($difference + $time_wood)*$wood_bonus;
        $new_time_stone = ($difference + $time_stone)*$stone_bonus;
        $new_time_bone = ($difference + $time_bone)*$bone_bonus;
        $new_time_flint = ($difference + $time_flint)*$flint_bonus;

        //jedno drewienko co 5 minut itd... i liczy modulo które to sekundy będą zapisywane w bazie
        //danych żeby dino nie tracił cennych sekund
        $new_wood = floor($new_time_wood/300);
        $new_wood_modulo = $new_time_wood%300;
        $new_stone = floor($new_time_stone/600);
        $new_stone_modulo = $new_time_stone%600;
        $new_bone = floor($new_time_bone/1000);
        $new_bone_modulo = $new_time_bone%1000;
        $new_flint = floor($new_time_flint/1500);
        $new_flint_modulo = $new_time_flint%1500;

        //zapisanie nowych ilości materiałów
        $dino_materia->setWood($new_wood + $wood);
        $dino_materia->setBone($new_bone + $bone);
        $dino_materia->setStone($new_stone + $stone);
        $dino_materia->setFlint($new_flint + $flint);

        //zapisanie ilości sekund jakie zostały niewykorzystane
        $dino_materia->setWoodExtra($new_wood_modulo);
        $dino_materia->setStoneExtra($new_stone_modulo);
        $dino_materia->setBoneExtra($new_bone_modulo);
        $dino_materia->setFlintExtra($new_flint_modulo);

        $dino_materia->setUpdatedAt($date);

        $this->em->persist($dino_materia);
        $this->em->flush();

        return $dino_materia;
    }


    /**
     * Sprawdza wymagania schronu
     * @param User $user
     * @return array
     */
    public function checkHomeRequirements(User $user){

        $dinoMaterialsId = $user->getMateria()->getId();
        $dino_materia = $this->em->getRepository('AddUserDinoBundle:DinoMaterials')->findOneById($dinoMaterialsId);

        //ilości surowców
        $wood = $dino_materia->getWood();
        $bone = $dino_materia->getBone();
        $stone = $dino_materia->getStone();
        $flint = $dino_materia->getFlint();

        //kolejne elementy tablicy reprezentuja kolejne schrony
        $home = [];
        //przypisywanie odpowiedniego poziomu dostępu w zależności od posiadanych surowców
        if($wood>60 && $stone>40 && $bone>15 && $flint>6){
            $home[0] = 0;
        }else{
            $home[0] = 0;
        }
        if($wood>120 && $stone>90 && $bone>40 && $flint>25){
            $home[1] = 1;
        }else{
            $home[1] = 0;
        }
        if($wood>250 && $stone>170 && $bone>100 && $flint>40){
            $home[2] = 2;
        }else{
            $home[2] = 0;
        }
        if($wood>340 && $stone>200 && $bone>130 && $flint>70){
            $home[3] = 3;
        }else{
            $home[3] = 0;
        }
        if($wood>400 && $stone>320 && $bone>290 && $flint>150){
            $home[4] = 4;
        }else{
            $home[4] = 0;
        }
        if($wood>560 && $stone>400 && $bone>310 && $flint>170){
            $home[5] = 5;
        }else{
            $home[5] = 0;
        }
        if($wood>900 && $stone>790 && $bone>650 && $flint>400){
            $home[6] = 6;
        }else{
            $home[6] = 0;
        }
        if($wood>1100 && $stone>850 && $bone>740 && $flint>500){
            $home[7] = 7;
        }else{
            $home[7] = 0;
        }

        return $home;
    }

}