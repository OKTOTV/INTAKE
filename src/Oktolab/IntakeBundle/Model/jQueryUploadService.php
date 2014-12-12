<?php

namespace Oktolab\IntakeBundle\Model;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oktolab\IntakeBundle\Entity\File;
use Oktolab\IntakeBundle\Entity\Source;

class jQueryUploadService
{

    private $em;
    private $mailer;
    private $templating;

    public function __construct($entityManager, $mailer, $templating)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Links a sourcefile in the database to a File
     */
    public function uploadedFile(PostPersistEvent $event)
    {
        $request = $even->getRequest();
        $uniqueID = $event->getRequest()->get('oktolab_intake_bundle_filetype')['uniqueID'];
        $file = $this->em->getRepository('OktolabIntakeBundle:File')->findOneBy( array('uniqueID' => $uniqueID) );

        if (!$file) { // file is not saved yet, create it
            $file = new File();
            $file->setUniqueID($uniqueID);
        }
        $source = new Source();
        $source->setName($event->getFile()->getFilename());
        $source->setPath($event->getFile()->getRealPath());

        $file->addSource($source);
        $source->setFile($file);

        $this->em->persist($file);
        $this->em->persist($source);
        $this->em->flush();
    }

    /**
     * Saves a file to the database. Adds info to an already saved file
     */
    public function saveFile(File $file) {

        $databaseFile = $this->em->getRepository('OktolabIntakeBundle:File')->findOneBy( array('uniqueID' => $file->getUniqueID()));

        if ($databaseFile) {
            $databaseFile->setSeries($file->getSeries());
            $databaseFile->setEpisodeName($file->getEpisodeName());
            $databaseFile->setEpisodeDescription($file->getEpisodeDescription());
            $databaseFile->setContact($file->getContact());

            $this->em->persist($databaseFile);
            //$this->moveSourcefiles($databaseFile);
            $this->sendMail($databaseFile);
        } else {
            $this->em->persist($file);
        }

        $this->em->flush();
    }

    // Send an Email to the selected reciptient
    public function sendMail(File $file) {
        $message = \Swift_Message::newInstance()
            ->setSubject('Neue Dateien abgegeben')
            ->setFrom(array('intake@okto.tv' => 'OKTOBOT'))
            ->setTo($file->getContact()->getEmail())
            ->setBody($this->templating->render('OktolabIntakeBundle:Backend:email.html.twig', array('file' => $file)), 'text/html');
        $this->mailer->send($message);
    }

    public function deleteFile(File $file) {

        foreach ($file->getSources() as $source) {
            //remove real files from the hard drive
            unlink($source->getPath());
            $this->em->remove($source);
        }
        $this->em->remove($file);

        $this->em->flush();
    }

    private function moveSourcefiles(File $file) {

        foreach ($file->getSources() as $source) {
            $info = new \SplFileInfo($source->getPath());
            $newPath = $info->getPath().'/'.$file->getSeries().' - '.$file->getEpisodeName().'_'.$source->getName();
            rename($source->getPath(), $newPath);
            $source->setPath($newPath);
            $this->em->persist($source);
        }
        $this->em->flush();
    }
}