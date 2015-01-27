<?php

namespace Oktolab\IntakeBundle\Model;

class MailService
{
    private $mailer;
    private $templating;
    private $from;
    private $from_name;

    public function __construct($mailer, $templating, $from, $from_name)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->from = $from;
        $this->from_name = $from_name;
    }

    /**
     * TODO: setFrom uses a hardcoded adress and not the parameters config info.
     * Sends a mail with your desired template.
     * @param  string $to the mail adress you like to send this email
     * @param  string $template  Something like OktolabIntakeBundle:Email:new_user.html.twig
     * @param  array  $arguments an array with all needed things for your template (don't forget _locale if needed)
     * @param  string $subject subject of your email
     */
    public function sendMail($to, $template, $arguments = array(), $subject = "INTAKE Nachricht")
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array($this->from => $this->from_name))
            ->setTo($to)
            ->setBody($this->templating->render($template, $arguments), 'text/html');
        $this->mailer->send($message);   
    }
}

