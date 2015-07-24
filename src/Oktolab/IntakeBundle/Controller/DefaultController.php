<?php

namespace Oktolab\IntakeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oktolab\IntakeBundle\Entity\File;
use Oktolab\IntakeBundle\Entity\Source;
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
        $translated = $this->get('translator')->trans('intake.file.submit_uploading');

        $form->add('save', 'submit', array('label' => 'intake.file.submit', 'attr' => array('data-uploading' => $translated)));

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($this->get('oktolab.upload_listener')->saveFile($file)) {
                    $this->get('session')->getFlashBag()->add('success', "intake.message.file_submit_success");
                    return $this->redirect($this->generateUrl('intake_success'));
                }

                $this->get('session')->getFlashBag()->add('error', "intake.message.file_submit_error");
                return $this->redirect($this->generateUrl('intake_success'));

            } else {
                $this->get('session')->getFlashBag()->add('error', "intake.message.file_submit_error");
            }
        }
        $databasefile = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:File')->findOneBy(array('uniqueID' => $file->getUniqueID()));
        return array('form' => $form->createView(), 'databasefile' => $databasefile, 'filesize' => $this->get('oktolab.upload_listener')->getTotalFilesize());
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
    * @Route("/about_okto", name="intake_about_okto")
    * @Template
    */
    public function aboutOktoAction()
    {
        return array();
    }

    /**
     * Allows download of all sources available
     * @Route("/download/{name}", name="intake_download")
     */
    public function downloadAction(Source $source)
    {
        $finfo = \finfo_open(FILEINFO_MIME_TYPE);

        if ($this->container->getParameter('xsendfile')) {
            $response = new Response();
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $source->getOriginalName()));
            $response->headers->set('Content-type', \finfo_file($finfo, $source->getPath()));
            $response->headers->set('X-Sendfile', $source->getPath());
            $response->sendHeaders();
            return $response;
        }
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', \finfo_file($finfo, $source->getPath()));
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"',$source->getOriginalName()));
        $response->headers->set('Content-length', filesize($source->getPath()));

        \finfo_close($finfo);

        // // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($source->getPath()));

        return $response;

    }
}
