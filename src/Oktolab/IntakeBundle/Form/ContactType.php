<?php

namespace Oktolab\IntakeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'intake.contact.name',
                    'attr' => array('placeholder' => 'intake.contact.name_placeholder')
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'label' => 'intake.contact.email',
                    'attr' => array('placeholder' => 'intake.contact.email_placeholder')
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Oktolab\IntakeBundle\Entity\Contact'
            )
        );
    }

    public function getName()
    {
        return 'oktolab_intake_bundle_contacttype';
    }
}
