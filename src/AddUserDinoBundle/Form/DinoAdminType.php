<?php

namespace AddUserDinoBundle\Form;

use Proxies\__CG__\AddUserDinoBundle\Entity\DinoParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AddUserDinoBundle\Form\DinoParametersType;


class DinoAdminType extends AbstractType
{

    /**
     * Formularz rozszerzający FosUserBundle, przeznaczony do edycji usera w panelu admina
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Dino-imię',
            ))
            ->add('species', ChoiceType::class, array(
                'choices'  => array(
                    'Roślinożerne' => array(
                        'Stegozaur'    => 'Stegozaur',
                        'Triceratops'  => 'Triceratops',
                        'Diplodok'     => 'Diplodok'
                    ),
                    'Mięsożerne' => array(
                        'Brachiozaur'  => 'Brachiozaur',
                        'Pteranodon'   => 'Pteranodon',
                        'Welociraptor' => 'Welociraptor'
                    ),
                    'Wszystkożerne' => array(
                        'Teropod'     => 'Teropod',
                        'Chirostenot'  => 'Chirostenot',
                        'Owiraptor'    => 'Owiraptor'
                    )
                ),
                'label' => 'Gatunki',
                'multiple' => false,
                'placeholder' => 'Wybierz gatunek',
            ))
            ->add('age', ChoiceType::class, array(
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21',
                    '22' => '22',
                    '23' => '23',
                    '24' => '24',
                    '25' => '25',
                    '26' => '26',
                    '27' => '27',
                    '28' => '28',
                    '29' => '29',
                    '30' => '30',
                ),
                'label' => 'Wiek',
                'multiple' => false,
                'placeholder' => 'Wybierz wiek',
                'attr' => ['data-help'  => 'W zależności od wieku Dinoid ma różne zdolności.']
            ))
            ->add('submit', SubmitType::class, array('label' => 'Edytuj'))

            ->add('dino', DinoParametersType::class, array(
                    'data_class' => 'AddUserDinoBundle\Entity\DinoParameters')
            )
            ->add('materia', DinoMaterialsType::class, array(
                    'data_class' => 'AddUserDinoBundle\Entity\DinoMaterials')
            )
            ->remove('username')
        ;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('AcmeRegistration', 'AcmeProfile'),
        ));
    }


//    ----Umożliwia dziedziczenie po formularzu fos------
//Źródło: http://symfony.com/doc/master/bundles/FOSUserBundle/overriding_forms.html

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }


    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

}
