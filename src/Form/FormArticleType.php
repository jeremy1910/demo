<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\ImageArticle;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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

        $req = $this->categoryRepository->getAllLibele();


        $builder

            ->add('title', TextType::class, ['label' => 'Titre de l\'article', 'attr' => ['placeholder' => 'Entrez un titre']])
            ->add('num_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'libele',
                'label' => 'Catégorie',

            ])
            ->add('tags', CollectionType::class, [

                'entry_type' => TagType::class,
                'allow_add' => true,
                'by_reference' => false
            ])
            ->add('image', ImageArticleType::class, ['label' => false])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['placeholder' => 'Entrez une description']])
            ->add('body', CKEditorType::class, ['label' => 'Corp de l\'article'])

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();


                if (!$data) {
                    return;
                }
                if(substr_count($data['num_category'], '|') == 0)
                {
                    return;
                }
                else
                {
                    $categoryTab = explode('|', $data['num_category']);

                    // Do nothing if the category with the given ID exists
                    if ($this->em->getRepository(Category::class)->find($categoryTab[1])) {
                        return;
                    }

                    // Create the new category
                    $category = new Category();
                    $category->setLibele($categoryTab[0]);
                    $this->em->persist($category);
                    $this->em->flush();

                    $data['num_category'] = $category->getId();
                    $event->setData($data);
                }

            });
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }


}
