<?php

namespace Oktolab\IntakeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oktolab\IntakeBundle\Entity\Role;

//TODO: check if roles already exist!
class WarmupCommand extends Command
{
    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('intake:warmup')
            ->setDescription('Installs Roles. Run this after installing or update');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userRole = $this->em->getRepository('OktolabIntakeBundle:Role')->findOneBy(array('name' => 'ROLE_USER'));
        $adminRole = $this->em->getRepository('OktolabIntakeBundle:Role')->findOneBy(array('name' => 'ROLE_ADMIN'));
        
        if (!$userRole) {
            $output->writeln('No USER Role found. Adding USER Role to Database.');

            $userRole = new Role();
            $userRole->setName('ROLE_USER');
            $this->em->persist($userRole);
        } else {
            $output->writeln('USER Role found');
        }

        if (!$adminRole) {
            $output->writeln('No ADMIN Role found. Adding ADMIN Role to Database.');
            $adminRole = new Role();
            $adminRole->setName('ROLE_ADMIN');

            $this->em->persist($adminRole);
        } else {
            $output->writeln('ADMIN Role found');
        }

        $this->em->flush();

        $output->writeln('Warmup Complete! You may want to add an ADMIN to the Database with >>intake:create_admin<< next');
    }
}