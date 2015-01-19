<?php

namespace Oktolab\IntakeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oktolab\IntakeBundle\Entity\User;

//TODO: remove the password option as it is not save to have it written in the commandline
// set it blank and use the password 'reset' option and send it via email

class UserCommand extends Command
{
    private $em;
    private $password_encoder;

    public function __construct($entityManager, $passwEnc)
    {
        $this->em = $entityManager;
        $this->password_encoder = $passwEnc;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('intake:create_admin')
            ->setDescription('creates an admin user')
            ->addOption('name', '-1', InputOption::VALUE_REQUIRED, 'name of the account')
            ->addOption('email', '-2', InputOption::VALUE_REQUIRED, 'email of the account')
            ->addOption('password', '-3', InputOption::VALUE_REQUIRED, 'password of the account')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user->setUsername($input->getOption('name'));
        $user->setEmail($input->getOption('email'));
        $password = $this->password_encoder->encodePassword($user, $input->getOption('password'));
        $user->setPassword($password);
        $role = $this->em->getRepository('OktolabIntakeBundle:Role')->findOneBy(array('name' => 'ROLE_ADMIN'));
        $user->addRole($role);

        $this->em->persist($user);
        $this->em->flush();
    }
}