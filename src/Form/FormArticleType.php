<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\ImageArticle;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Valid;

class FormArticleType extends AbstractType
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var ObjectManager
     */
    private $em;


    public function __construct(CategoryRepository $categoryRepository, ObjectManager $em)
    {

        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /**
         * @var $article Article
         */
        $article = $builder->getData();

        $builder

            ->add('title', TextType::class, ['label' => 'Titre de l\'article', 'attr' => ['placeholder' => 'Entrez un titre']])
            ->add('num_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'libele',
                'label' => 'Catégorie',
    //            'attr' => ['class' => 'selectpicker'],

            ])
            ->add('tags', CollectionType::class, [

                'entry_type' => TagType::class,
                'allow_add' => true,
                'by_reference' => false
            ])
            ->add('image', ImageArticleType::class,
                [
                    'label' => false,
                    'required' => $article->getImage() != null ? false : true,
                    'constraints' => array(new Valid()),
                ])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['placeholder' => 'Entrez une description']])
            ->add('body', CKEditorType::class, ['label' => 'Corp de l\'article'])

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();


                if (!$data) {
                    return;
                }
                // Do nothing if the category with the given ID exists
                if ($this->em->getRepository(Category::class)->find($data['num_category'])) {
                    return;
                }

                // Create the new category
                $category = new Category();
                $category->setLibele($data['num_category']);
                $this->em->persist($category);
                $this->em->flush();

                $data['num_category'] = $category->getId();
                $event->setData($data);



            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){


            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,

        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        $choices = $view->vars['form']->children['num_category']->vars['choices'];

        $choices[] = new ChoiceView([], 'add', 'Ajouter une catégorie');
        $view->vars['form']->children['num_category']->vars['choices'] = $choices;


    }
}
