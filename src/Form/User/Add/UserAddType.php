<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 19/07/19
 * Time: 01:39
 */

namespace App\Form\User\Add;


use App\Entity\Roles;
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
            'label' => "Nom de l'utilisateur",
            'required' => false,
            'attr' => [
                'placeholder' => "Nom de l'utilisateur à créer",
            ]
        ])
            ->add('roles', EntityType::class, [
                'class' => Roles::class,
                'choice_label' => 'libele',
                'attr' => ['class' => 'selectpicker'],
            ])
            ->add('adresseMail', TextType::class, [
                'label' => "Adresse mail",
                'attr' => [
                    'placeholder' => 'Adresse mail de l\'utilisateur',
                ],

            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être les même',
                //'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
            ])
            ->add('enable',ChoiceType::class, [
                'label' => "Activer l'utilisateur ?",
                'choices' => [
                        'Oui' => '1',
                        'Non' => '0'
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
            'data_class' => null
        ]);
    }
}