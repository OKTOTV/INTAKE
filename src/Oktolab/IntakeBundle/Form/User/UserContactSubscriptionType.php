<?php

namespace Oktolab\IntakeBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class UserContactSubscriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'contacts',
                'entity',
                array(
                    'class' => 'OktolabIntakeBundle:Contact',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.order', 'ASC');
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'label'    => 'intake.user.contacts_label'
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oktolab\IntakeBundle\Entity\IntakeUser',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oktolab_intake_bundle_userSettingsType';
    }
}