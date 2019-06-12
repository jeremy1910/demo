<?php

namespace App\Form\Article\Filter;

use App\Entity\Category;
use App\Entity\Article\Filter\ArticleFilter;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFilterType extends AbstractType
{

    private const NB_RESULT = 25;
    private const PAGE_SELECTED = 0;

    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Recherche par titre'],
            ])
            ->add('createdAtBefore', DateType::class, [
            'required' => false,
                'widget' => 'single_text',
                'label' => 'créé avant le :',

            ])
            ->add('createdAtAfter', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'créé apres le :',
            ])
            ->add('last_edit', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'dernière modification le :',
            ])
            ->add('body', TextType::class, [
                'required' => false,
                'label' => 'Corp',
                'attr' => ['placeholder' => 'Recherche dans le corp'],
            ])
            ->add('numCategory',  ChoiceType::class,[
                'choices' => $this->categoryLibeleList(),
                'label' => 'Catégorie',
                'multiple' => true,
                'attr' => ['class' => 'selectpicker'],
                'required' => false,])
            ->add('image', null, [
                'required' => false
            ])
            ->add('description',null,  [
                'required' => false
            ])
            ->add('tags', CollectionType::class, [
                'allow_add' => true,
                'entry_type' => TextType::class,
                'required' => false,
                'label' => 'Tags',

            ])
            ->add('user', null, [
                'required' => false,
                'label' => 'Auteur',
                'attr' => ['placeholder' => 'Recherche par auteur'],
            ])
            ->add('nbResult', HiddenType::class, [
                'data' => self::NB_RESULT,
            ])
            ->add('pageSelected', HiddenType::class, [
                'data' => self::PAGE_SELECTED,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleFilter::class,
        ]);
    }

    private function categoryLibeleList()
    {
        $tab = $this->categoryRepository->getAllLibele();
        $tabToReturn = array();

        foreach ($tab as $key => $value){
            $tabToReturn[$value['libele']] = $value['id'];
        }
         return $tabToReturn;
    }
}
