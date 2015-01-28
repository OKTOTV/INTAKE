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
     */
    public function saveFile(File $file) {

        $databaseFile = $this->em->getRepository('OktolabIntakeBundle:File')->findOneBy( array('uniqueID' => $file->getUniqueID()));

        if ($databaseFile) {
            $databaseFile->setSeries($file->getSeries());
            $databaseFile->setEpisodeDescription($file->getEpisodeDescription());
            $databaseFile->setContact($file->getContact());
            $databaseFile->setUploaderEmail($file->getUploaderEmail());

            $this->em->persist($databaseFile);
                    $this->mailer->sendMail(
                $file->getContact()->getEmail(),
                'OktolabIntakeBundle:Email:new_file.html.twig',
                array('file' => $databaseFile),
                'Neue Dateien abgegeben'
            );
        } else {
            $this->em->persist($file);
        }

        $this->em->flush();
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
}