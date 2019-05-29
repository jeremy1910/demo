<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 25/05/19
 * Time: 01:28
 */

namespace App\Form\Filter;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCategoryType extends AbstractType
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
            ->add('libele', TextType::class, [
                'label' => 'Libele',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Libele à rechercher',
                ],
            ])
            ->add('newLibele', TextType::class,[
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Libele catégorie a créer',
                ],
            ])
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

}