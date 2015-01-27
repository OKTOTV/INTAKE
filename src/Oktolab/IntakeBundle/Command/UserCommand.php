<?php

namespace Oktolab\IntakeBundle\Command;

// use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oktolab\IntakeBundle\Entity\User;

//class UserCommand extends Command
class UserCommand extends ContainerAwareCommand 
{
    private $em;
    private $mailer;
    private $mailerHost;

    public function __construct($entityManager, $mailer, $mailerHost)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->mailerHost = $mailerHost;
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
            ->addOption('email', '-2', InputOption::VALUE_REQUIRED, 'email of the account. Needed for password creation');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($this->mailerHost);

        $user = new User();
        $user->setIsActive(true);
        $user->setUsername($input->getOption('name'));
        $user->setEmail($input->getOption('email'));

        $role = $this->em->getRepository('OktolabIntakeBundle:Role')->findOneBy(array('name' => 'ROLE_ADMIN'));
        $user->addRole($role);

        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->sendMail(
            $user->getEmail(), 
            'OktolabIntakeBundle:Email:new_user.html.twig', 
            array('user' => $user), 
            'INTAKE Willkommen'
        );
    }
}