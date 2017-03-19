<?php

namespace AddUserDinoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AddUserDinoBundle\Entity\User;
use AddUserDinoBundle\Entity\DinoParameters;
use Symfony\Component\HttpFoundation\Response;


class DinoType extends AbstractType
{

    /**
     * Jak pozbyć się własności username i używać tylko email'a
     * źródło: http://stackoverflow.com/questions/8832916/remove-replace-the-username-field-with-email-using-fosuserbundle-in-symfony2/21064627
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'Dino-imię',
                    //Dodanie info pod polem formularza
                    //Źródło: http://ajaxray.com/blog/symfony-forms-twitter-bootstrap-3-help-block/
                'attr' => ['data-help'  => 'Dino-imiona zaczynają się na literę D(d).']
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
                    'disabled' => $options['is_edit'],//REST course 1 odc. 15
                    'label' => 'Gatunki',
//                    'choice_name' => 'choice_name',
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
                ->add('submit', SubmitType::class, array('label' => 'Wykluj'))

            ->remove('username');
//
        ;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('AcmeRegistration', 'AcmeProfile'),
            'is_edit' => false, //REST course 1 odc. 15
            'csrf_protection' => true //na potrzeby api
        ));
    }

//    ----Umożliwia dziedziczenie po formularzu fos------
//Źródło: http://symfony.com/doc/master/bundles/FOSUserBundle/overriding_forms.html

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

//    -----------------------------------


}
