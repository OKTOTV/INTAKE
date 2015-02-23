<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                array(
                    'label' => 'intake.user.name',
                    'attr'  => array('placeholder' => 'intake.user.name_placeholder')
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bprs\UserBundle\Entity\Contact',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oktolab_intake_bundle_userContactType';
    }
}