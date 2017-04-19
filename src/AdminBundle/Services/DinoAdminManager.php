<?php

namespace AdminBundle\Services;

use AddUserDinoBundle\Entity\DinoImage;
use AddUserDinoBundle\Form\DinoAdminType;
use AddUserDinoBundle\Form\DinoMaterialsType;
use AddUserDinoBundle\Form\DinoParametersType;
use AddUserDinoBundle\Form\UserType;
use Doctrine\ORM\EntityManager;
use AddUserDinoBundle\Entity\DinoParameters;
use AddUserDinoBundle\Entity\DinoMaterials;
use AddUserDinoBundle\Entity\User;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DinoManager
 * @package AddUserDinoBundle\Services
 */
class DinoAdminManager
{
    private $em;
    /**
     * @var FormFactory
     */
    private $form;

    /**
     * @param EntityManager         $em
     */
    public function __construct(EntityManager $em, FormFactory $form)
    {
        $this->em = $em;
        $this->form = $form;
    }


    /**
     * Sprawdza który formularz został wybrany i zapisuje dane do bazy danych
     * @param Request $request
     * @param $user
     * @param $formType
     * @return array|bool
     */
    public function saveAdminForm(Request $request, $user, $formType )
    {
        $parameters = $user->getDino();
        $materials = $user->getMateria();

        if ($formType == 'parameters') {
            $editForm = $this->form->create(DinoParametersType::class, $parameters);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $this->em->persist($parameters);
                $this->em->flush();
            }

            return [$editForm, 'parameters'];

        } else if($formType == 'materials') {
            $editForm = $this->form->create(DinoMaterialsType::class, $materials);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $this->em->persist($materials);
                $this->em->flush();
            }

            return [$editForm, 'materials'];

        } else if ($formType == 'user') {
            $editForm = $this->form->create(UserType::class, $user);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $this->em->persist($user);
                $this->em->flush();
            }

            return [$editForm, 'user'];

        } else {
            return false;
        }

    }

    /**
     * Funkcja sprawdza który formularz był edytowany i przypisuje go do
     * przesłanego przez użytkownika
     * @param $user
     * @param $editForm
     * @return array
     */
    public function checkEditedForm($user, $editForm)
    {
        /** @var DinoMaterials */
        $materials = $user->getMateria();
        /** @var DinoParameters */
        $parameters = $user->getDino();

        $forms = [];
        $editUserForm = $this->form->create(UserType::class, $user);
        $editParametersForm = $this->form->create(DinoParametersType::class, $parameters);
        $editMaterialsForm = $this->form->create(DinoMaterialsType::class, $materials);
        if ($editForm[1] == 'user') {
            $editUserForm = $editForm[0];
            $forms[3] = 0;
        } else if ($editForm[1] == 'parameters') {
            $editParametersForm = $editForm[0];
            $forms[3] = 2;
        } else if ($editForm[1] == 'materials'){
            $editMaterialsForm = $editForm[0];
            $forms[3] = 1;
        }

        $forms[0] = $editUserForm;
        $forms[1] = $editParametersForm;
        $forms[2] = $editMaterialsForm;
        return $forms;
    }


}