<?php

namespace Oktolab\IntakeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'series',
                'text',
                array(
                    'label' => 'intake.file.series',
                    'attr' => array('placeholder' => 'intake.file.series_placeholder')
                )
            )
            ->add(
                'episodeDescription',
                'textarea',
                array(
                    'label' => 'intake.file.episode_description',
                    'attr' => array('placeholder' => 'intake.file.episodeDescription_placeholder', 'style' => 'height:200px')
                )
            )
            ->add(
                'readAGB',
                'checkbox',
                array(
                    'label' => 'intake.file.readAGB'
                )
            )
            ->add(
                'uploaderEmail',
                'email',
                array(
                    'label' => 'intake.file.uploaderEmail',
                    'attr' => array('placeholder' => 'intake.file.uploaderEmail_placeholder')
                )
            )
            ->add(
                'contact',
                'entity',
                array(
                    'label' => 'intake.file.contact',
                    'class' => 'OktolabIntakeBundle:Contact',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                        ->orderBy('u.order', 'ASC');
                    },
                    'property' => 'name',
                    'required'    => true,
                    'placeholder' => 'intake.file.choose_contact',
                )
            )
            ->add(
                'uniqueID',
                'hidden'
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Oktolab\IntakeBundle\Entity\File'
            )
        );
    }

    public function getName()
    {
        return 'oktolab_intake_bundle_filetype';
    }
}
