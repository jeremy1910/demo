<?php

namespace App\Form\Category\Filter;

use App\Entity\Category\Filter\CategoryFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFilterType extends AbstractType
{
    private const NB_RESULT = 10;
    private const PAGE_SELECTED = 1;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', NumberType::class, [
                'label' => 'Id de la catégorie',
                'attr' => ['placeholder' => 'ID de la catégorie Recherchée']
            ])
            ->add('libele', TextType::class, [
                'label' => 'Nom de la categorie',
                'attr' => ['placeholder' => 'Recherche par nom de catégorie']
            ])
            ->add('nbResult', HiddenType::class, [
                'data' => self::NB_RESULT,
            ])
            ->add('pageSelected', HiddenType::class, [
                'data' => self::PAGE_SELECTED,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryFilter::class,
        ]);
    }
}
