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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddType extends AbstractType
{
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
            ->add('roles', EntityType::class, [
                'class' => Roles::class,
                'choice_label' => 'libele',
              //  'required' => true,
               'attr' => ['class' => 'selectpicker'],
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
                'required' => true,
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
                'expanded'=>true
            ])
            ->add('submit', SubmitType::class);
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}