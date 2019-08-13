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
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
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
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'required' => false,
                'attr' => [
                    'placeholder' => "Nom d'utilisateur à rechercher",
                ]
            ])
            ->add('roles', EntityType::class, [
                'class' => Roles::class,
                'choice_label' => 'libele',
                'attr' => ['class' => 'selectpicker'],

            ])
            ->add('enable', ChoiceType::class, [
                'choices' => [
                    'Actif' => 1,
                    'Inactif' => 0,
                ],
                'attr' => ['class' => 'selectpicker'],
                'required' => false,
            ])
            ->add('adresseMail')
            ->add('nbResult', HiddenType::class, [
                'data' => self::NB_RESULT,
            ])
            ->add('pageSelected', HiddenType::class, [
                'data' => self::PAGE_SELECTED,
            ])
            ->add('submit', SubmitType::class);
        ;


        $builder->addEventListener(FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {

                if ($event->getData()['roles'] === 'All') {
                    $data = $event->getData();
                    $data['roles'] = null;
                    $event->setData($data);

                }

            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserFilter::class,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        $choices = $view->vars['form']->children['roles']->vars['choices'];

        $choices[] = new ChoiceView([], 'All', 'Tous');
        $view->vars['form']->children['roles']->vars['choices'] = $choices;


    }


}