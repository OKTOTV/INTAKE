<?php

namespace Oktolab\IntakeBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oktolab\IntakeBundle\Entity\User;

class UserCommand extends Command
{
    private $em;
    private $mailer;
    private $templating;


    public function __construct($entityManager, $mailer, $templating)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
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
        $user = new User();
        $user->setIsActive(true);
        $user->setUsername($input->getOption('name'));
        $user->setEmail($input->getOption('email'));

        $role = $this->em->getRepository('OktolabIntakeBundle:Role')->findOneBy(array('name' => 'ROLE_ADMIN'));
        $user->addRole($role);

        $this->em->persist($user);
        $this->em->flush();

        //TODO: send email
        $message = \Swift_Message::newInstance()
            ->setSubject('INTAKE Willkommen')
            ->setFrom(array('intake@okto.tv' => 'OKTOBOT'))
            ->setTo($user->getEmail())
            ->setBody($this->templating->render('OktolabIntakeBundle:Email:new_user.html.twig', array('user' => $user)), 'text/html');
        $this->mailer->send($message);
    }
}