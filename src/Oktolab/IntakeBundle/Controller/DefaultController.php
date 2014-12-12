<?php

namespace Oktolab\IntakeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oktolab\IntakeBundle\Entity\File;
use Oktolab\IntakeBundle\Form\FileType;

class DefaultController extends Controller
{
    /**
     * Default page. Lets users upload files!
     * @Route("/", name="intake_new", defaults={"_locale" = "de"})
     * @Template
     */
    public function indexAction(Request $request)
    {
        $file = new File();
        $form = $this->createForm(new FileType(), $file);
        $form->add('save', 'submit', array('label' => 'intake.file.submit'));

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);

            if ($form->isValid()) { 
                $this->get('oktolab.upload_listener')->saveFile($file);

                $this->get('session')->getFlashBag()->add('success', "intake.message.file_submit_success");
                return $this->redirect($this->generateUrl('intake_success'));

            } else {
                $this->get('session')->getFlashBag()->add('error', "intake.message.file_submit_error"); 
            }
        }
        $databasefile = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:File')->findOneBy(array('uniqueID' => $file->getUniqueID()));
        return array('form' => $form->createView(), 'databasefile' => $databasefile);
    }

    /**
     * Show after successful sent File
     * @Route("/success", name="intake_success")
     * @Template
     */
    public function successAction()
    {
        return array();
    }

    /**
     * Delete Files in the backend
     * @Route("/about", name="intake_about")
     * @Template
     */
    public function aboutAction(Request $request)
    {
        $license = file_get_contents($this->get('kernel')->getRootDir().'/../LICENSE');
        return array('license' => $license);
    }

    /**
     * Allows download of all sources available
     * @Route("/download/{file_id}/{source_id}", name="intake_download")
     */
    public function download($file_id, $source_id)
    {
        $file = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:File')->findOneBy(array('uniqueID' => $file_id));
        if (!$file) { // already deleted
            $this->get('session')->getFlashBag()->add('warning', 'intake.file.already_deleted');
            return $this->redirect($this->generateUrl('intake_new'));
        }
        $found_source = null;

        foreach ($file->getSources() as $source) {
            if ($source->getId() == $source_id) {
                $found_source = $source;
            }
        }

        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($source->getPath()));
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"',$source->getName()));
        $response->headers->set('Content-length', filesize($source->getPath()));

        // // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($source->getPath()));

        return $response;
    }
}