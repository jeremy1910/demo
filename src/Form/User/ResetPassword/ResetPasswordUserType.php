<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 02/08/19
 * Time: 23:50
 */

namespace App\Form\User\ResetPassword;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent être les même',
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe', 'attr' => ['placeholder' => "Mot de passe de l'utilisateur",]],
            'second_options' => ['label' => 'Répétez le mot de passe', 'attr' => ['placeholder' => "Entrez de nouveau le même mot de passe",]],
            'constraints' => [new Regex(['pattern' => "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,50}$/", 'message' => "New password is required to be minimum 6 chars in length and to include at least one letter and one number."] ),
                new NotBlank(['message' => 'Le mot de passe ne peut pas être vide'])],
            'attr' => [
                'placeholder' => "Mot de passe de l'utilisateur",
            ],


        ])
        ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => null,
        ]);
    }
}