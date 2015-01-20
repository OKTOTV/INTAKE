<?php

namespace Oktolab\IntakeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username', 
                'text', 
                array(
                    'label' => 'intake.user.name', 
                    'attr' => array('placeholder' => 'intake.user.name_placeholder')
                )
            )
            ->add(
                'email', 
                'email', 
                array(
                    'label' => 'intake.user.email',
                    'attr' => array('placeholder' => 'intake.user.email_placeholder')
                )
            )
            ->add(
                'roles', 
                'collection', 
                array(
                    'type'  => 'entity',
                    'options' => array('class' => 'Oktolab\IntakeBundle\Entity\Role', 'property' => 'id'),
                    'allow_add' => true,
                    'allow_delete' => true
                )
            )
            ->add(
                'isActive', 
                'checkbox', 
                array(
                    'label' => 'intake.user.isActive'
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Oktolab\IntakeBundle\Entity\User'
            )
        );
    }

    public function getName()
    {
        return 'oktolab_intake_bundle_usertype';
    }
}