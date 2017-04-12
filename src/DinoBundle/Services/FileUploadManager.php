<?php

namespace DinoBundle\Services;

use AddUserDinoBundle\Entity\DinoParameters;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class FileUploadManager{

    private $em;
    /**
     * @var FormBuilder
     */

    private $form;

    /**
     * @param EntityManager $em
     * @param FormFactory $form
     */
    public function __construct(EntityManager $em, FormFactory $form)
    {
        $this->em = $em;
        $this->form = $form;
    }


    public function saveUploadedFile(Request $request, $dinoParameters)
    {

    }




}