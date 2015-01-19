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
        $userRole = new Role();
        $userRole->setName('ROLE_USER');
        $adminRole = new Role();
        $adminRole->setName('ROLE_ADMIN');

        $this->em->persist($userRole);
        $this->em->persist($adminRole);

        $this->em->flush();
    }
}