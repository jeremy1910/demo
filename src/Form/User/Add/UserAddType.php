<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 19/07/19
 * Time: 01:39
 */

namespace App\Form\User\Add;


use App\Entity\Roles;
use App\Entity\User;
use App\Entity\User\Filter\UserFilter;
use App\Form\RolesType;
use App\Repository\RolesRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddType extends AbstractType
{
    private $rolesRepository;
    private $userRepository;

    public function __construct(RolesRepository $rolesRepository, UserRepository $userRepository)
    {
        $this->rolesRepository = $rolesRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('username', TextType::class, [
            'label' => "Nom de connexion",
            'required' => true,
            'attr' => [
                'placeholder' => "Nom de connexion",
            ]
        ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom",
                'required' => true,
                'attr' => [
                    'placeholder' => "Prénom de l'utilisateur",
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => "Nom",
                'required' => true,
                'attr' => [
                    'placeholder' => "Nom de l'utilisateur",
                ]
            ])

            ->add('roles', ChoiceType::class, [
                'attr' => ['class' => 'selectpicker'],
                'choices' => $this->genRolesChoice(),
                'data' => $this->getDefaultSelectedRole($options),


            ])
            ->add('adresseMail', TextType::class, [
                'label' => "Adresse mail",
                'required' => true,
                'attr' => [
                    'placeholder' => 'Adresse mail de l\'utilisateur',
                ],

            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être les même',
                'required' => false,

                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['placeholder' => "Mot de passe de l'utilisateur",]],
                'second_options' => ['label' => 'Répétez le mot de passe', 'attr' => ['placeholder' => "Entrez de nouveau le même mot de passe",]],
                'attr' => [
                    'placeholder' => "Mot de passe de l'utilisateur",
                ]
            ])
            ->add('enable',ChoiceType::class, [
                'label' => "Activer l'utilisateur ?",
                'required' => true,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'multiple'=>false,
                'expanded'=>true,

            ])
            ->add('submit', SubmitType::class)

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {

                if(! key_exists('password', $event->getData())) {
                   $form = $event->getForm();
                   $form->remove('password');
                }
                if(! key_exists('username', $event->getData())) {
                   $form = $event->getForm();
                   $form->remove('username');
                }
                if(! key_exists('lastName', $event->getData())) {
                   $form = $event->getForm();
                   $form->remove('lastName');
                }
                if(! key_exists('firstName', $event->getData())) {
                   $form = $event->getForm();
                   $form->remove('firstName');
                }
                if(! key_exists('enable', $event->getData())) {
                   $form = $event->getForm();
                   $form->remove('enable');
                }
                if(! key_exists('roles', $event->getData())) {
                   $form = $event->getForm();
                   $form->remove('roles');
                }

            })
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'selected_user' => null,

        ]);

    }

    private function genRolesChoice(){
        $roles = $this->rolesRepository->findAll();
        $returnTab = array();
        /**
         * @var $role Roles
         */
        foreach ($roles as $key => $role){
            $returnTab[$role->getLibele()] = $role;
        }

        return $returnTab;
    }

    private function getDefaultSelectedRole(array $options){
        if(!is_null($options['selected_user'])){
            $user = $this->userRepository->find($options['selected_user']);
            $roleName = $user->getRoles()[0];
            /**
             * @var $role Roles[]
             */
            $role = $this->rolesRepository->findBy(['roleName' => $roleName]);

            return $role[0];
        }
        else{
            return [];
        }
    }
}