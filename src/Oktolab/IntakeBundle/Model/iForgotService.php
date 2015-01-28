<?php

namespace Oktolab\IntakeBundle\Model;

use Oktolab\IntakeBundle\Entity\User;

class iForgotService
{
    private $em;
    private $mailer;
    private $templating;

    public function __construct($entityManager, $mailer, $passwEnc)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
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

            $this->mailer->sendMail(
                $user->getEmail(),
                'OktolabIntakeBundle:Email:iforgot.html.twig',
                array('user' => $user),
                'INTAKE Passwort zurücksetzen'
            );
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
}