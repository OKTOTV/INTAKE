<?php

namespace Oktolab\IntakeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oktolab\IntakeBundle\Entity\User as User;

//TODO: remove the password option as it is not save to have it written in the commandline
// set it blank and use the password 'reset' option and send it via email

class UserCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('intake:create_admin')
            ->setDescription('creates an admin user')
            ->addOption('name', '-n', InputOption::VALUE_REQUIRED, 'name of the account')
            ->addOption('email', '-e', InputOption::VALUE_REQUIRED, 'email of the account')
            ->addOption('password', '-p', InputOption::VALUE_REQUIRED, 'password of the account')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user->setName($input->getOption('name'));
        $user->setEmail($input->getOption('email'));
        $user->setPassword($input->getOption('password'));
    }
}