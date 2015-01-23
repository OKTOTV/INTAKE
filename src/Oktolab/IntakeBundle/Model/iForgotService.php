<?php

namespace Oktolab\IntakeBundle\Model;

use Oktolab\IntakeBundle\Entity\User;

class iForgotService
{
    private $em;
    private $mailer;
    private $templating;

    public function __construct($entityManager, $mailer, $templating, $passwEnc)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->password_encoder = $passwEnc;
    }

    /**
     * Prepares user for new password and sends email if done.
     */
    public function resetPassword($username)
    {
        $user = $this->em->getRepository('OktolabIntakeBundle:User')->findOneBy(array('username' => $username));

        if ($user) {
            $hash = openssl_random_pseudo_bytes(10);
            $user->setResetHash(sha1($hash));
            $this->em->persist($user);
            $this->em->flush();

            $this->sendMail($user);
        }
    }

    /**
     * sets new password and removes hash to disable old link
     */
    public function setPassword($password, $resetHash)
    {
        $user = $this->em->getRepository('OktolabIntakeBundle:User')->findOneBy(array('resetHash' => $resetHash));

        if ($user) {
            $password = $this->password_encoder->encodePassword($user, $password);
            $user->setPassword($password);
            $user->setResetHash(null);
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function sendMail(User $user) {
        $message = \Swift_Message::newInstance()
            ->setSubject('INTAKE Passwort zurücksetzen')
            ->setFrom(array('intake@okto.tv' => 'OKTOBOT'))
            ->setTo($user->getEmail())
            ->setBody($this->templating->render('OktolabIntakeBundle:Backend:iforgot.html.twig', array('user' => $user)), 'text/html');
        $this->mailer->send($message);
    }
}