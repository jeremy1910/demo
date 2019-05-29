<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 18/05/19
 * Time: 21:10
 */

namespace App\Form\AdminDashboard;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleDashboardFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Titre',
            'required' => false,
        ])
            ->add('numCategory', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'libele',
                'label' => 'Catégorie',
                'multiple' => true,
                'attr' => ['class' => 'selectpicker'],
                'required' => false,

            ])
            ->add('user', TextType::class,[
                'label' => 'Auteur',
                'required' => false,
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tag',
                'required' => false,
            ])
            ->add('created_after', DateType::class,[
                'widget' => 'single_text',
                'label' => 'créé apres le :',
                'required' => false,
            ])
            ->add('created_before', DateType::class,[
                'widget' => 'single_text',
                'label' => 'créé avant le :',
                'required' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Article::class,
        ]);
    }

}