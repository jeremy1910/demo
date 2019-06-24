<?php


namespace App\Form\Tag;


use App\Entity\Tag\Filter\TagFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagFilterType extends AbstractType
{

    private const NB_RESULT = 10;
    private const PAGE_SELECTED = 1;


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
            ->add('nbResult', HiddenType::class, [
                'data' => self::NB_RESULT,
            ])
            ->add('pageSelected', HiddenType::class, [
                'data' => self::PAGE_SELECTED,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ])
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TagFilter::class,
        ]);
    }

}