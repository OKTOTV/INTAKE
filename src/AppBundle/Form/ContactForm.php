<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Bprs\AssetBundle\Form\Type\AssetCollectionType;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'intake.contact.name_label',
                    'attr' => ['placeholder' => 'intake.contact.name_placeholder']
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'intake.contact.email_label',
                    'attr' => ['placeholder' => 'intake.contact.email_placeholder']
                ]
            )
            ->add(
                'sortNumber',
                IntegerType::class,
                [
                    'label' => 'intake.contact.sortNumber_label'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\Contact'
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'Contact_Formtype';
    }
}
