<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Bprs\AssetBundle\Form\Type\AssetCollectionType;

class ProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'intake.project.name_label',
                    'attr' => ['placeholder' => 'intake.project.name_placeholder']
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'intake.project.description_label',
                    'attr' => ['placeholder' => 'intake.project.description_placeholder']
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'intake.project.email_label',
                    'attr' => ['placeholder' => 'intake.project.email_placeholder']
                ]
            )
            ->add(
                'readAGB',
                CheckboxType::class,
                [
                    'label' => 'intake.project.readAGB_label',
                    'mapped' => false
                ]
            )
            ->add(
                'assets',
                AssetCollectionType::class,
                ['label' => 'intake.project.assets_label']
            )
            ->add(
                'contact',
                EntityType::class,
                [
                    'label' => 'intake.project.contact_label',
                    'class' => 'AppBundle:Contact',
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.sortNumber', 'ASC');
                    },
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\Project'
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'Project_Formtype';
    }
}
