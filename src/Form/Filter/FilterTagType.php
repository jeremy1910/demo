<?php


namespace App\Form\Filter;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterTagType extends AbstractType
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
            ->add('tagName', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom du tag à rechercher',
                ],
            ])
            
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}