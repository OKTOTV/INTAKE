<?php

namespace Oktolab\IntakeBundle\Model;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Event\PreUploadEvent;
use Oktolab\IntakeBundle\Entity\File;
use Oktolab\IntakeBundle\Entity\Source;

class jQueryUploadService
{
    private $em;
    private $mailer;
    private $originalName;

    public function __construct($entityManager, $mailer)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
    }

    /**
     * Links a sourcefile in the database to a real File in your Filesystem
     */
    public function uploadedFile(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        $uniqueID = $event->getRequest()->get('oktolab_intake_bundle_filetype')['uniqueID'];
        $file = $this->em->getRepository('OktolabIntakeBundle:File')->findOneBy( array('uniqueID' => $uniqueID) );

        if (!$file) { // file is not saved yet, create it
            $file = new File();
            $file->setUniqueID($uniqueID);
        }
        $source = new Source();

        $source->setName($event->getFile()->getFilename());
        $source->setPath($event->getFile()->getRealPath());
        $source->setFilesize(filesize($source->getPath()));
        $source->setOriginalName($this->originalName);

        $file->addSource($source);
        $source->setFile($file);

        $this->em->persist($file);
        $this->em->persist($source);
        $this->em->flush();
    }

    public function onUpload(PreUploadEvent $event)
    {
        $this->originalName = $event->getFile()->getClientOriginalName();
    }

    /**
     * Saves a file to the database. Adds info to an already saved file
     * @return  boolean [true if files contains sources, false if otherwise]
     */
    public function saveFile(File $file) {

        $databaseFile = $this->em->getRepository('OktolabIntakeBundle:File')->findOneBy( array('uniqueID' => $file->getUniqueID()));

        if ($databaseFile) { //already tried to send something?
            $databaseFile->setSeries($file->getSeries());
            $databaseFile->setEpisodeDescription($file->getEpisodeDescription());
            $databaseFile->setContact($file->getContact());
            $databaseFile->setUploaderEmail($file->getUploaderEmail());

            $this->em->persist($databaseFile);
            $this->em->flush();

            if (count($databaseFile->getSources()) > 0) {
                $this->sendOkayMail($databaseFile);
                return true;
            }

            $this->sendErrorMail($databaseFile);
            return false;

        } else { //files does not exist, something went wrong

            $this->em->persist($file);
            $this->em->flush();

            $this->sendErrorMail($file);
            return false;
        }
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

    public function getTotalFilesize()
    {
        $sources = $this->em->getRepository('OktolabIntakeBundle:Source')->findAll();
        $size = 0;
        foreach ($sources as $source) {
            $size += $source->getFilesize();
        }

        return $size;
    }

    private function sendOkayMail(File $file)
    {
        $this->mailer->sendMail(
                $file->getContact()->getEmail(),
                'OktolabIntakeBundle:Email:new_file.html.twig',
                array('file' => $file),
                'Neue Dateien abgegeben'
            );
    }

    private function sendErrorMail(File $file)
    {
        $this->mailer->sendMail(
                $file->getContact()->getEmail(),
                'OktolabIntakeBundle:Email:error_file.html.twig',
                array('file' => $file),
                'Fehler bei Abgabe'
            );
    }
}
