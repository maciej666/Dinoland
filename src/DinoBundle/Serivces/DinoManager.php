<?php

namespace DinoBundle\Services;

use Doctrine\ORM\EntityManager;
use LogBundle\Entity\User;
use DinoBundle\Entity\Dino;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class DinoManager{

    private $em;

    /**
     * @param EntityManager         $em
     *
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }






}