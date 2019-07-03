<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 29/06/19
 * Time: 14:11
 */

namespace App\Form\User\Filter;


use App\Entity\Roles;
use App\Entity\User\Filter\UserFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class, [
            'label' => 'ID',
            'required' => false,
            'attr' => [
                'placeholder' => 'ID à rechercher',
            ],

        ])
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'required' => false,
                'attr' => [
                    'placeholder' => "Nom d'utilisateur à rechercher",
                ]
            ])
            ->add('roles', EntityType::class, [
                'class' => Roles::class,
                'choice_label' => 'roleName',
            ])
            ->add('enable')
            ->add('adresseMail')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserFilter::class,
        ]);
    }

}