<?php

namespace Oktolab\IntakeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Oktolab\IntakeBundle\Form\User\UserContactSubscriptionType;

/**
 * @Route("/backend/user")
 */
class UserController extends Controller
{
    /**
     * lists all files a given contact where sent to
     * @Route("/my_files", name="intake_my_files")
     * @Template
     */
    public function filesAction()
    {
        $contacts = $this->getUser()->getExtendedUser()->getContacts();
        return array('contacts' => $contacts);
    }

    /**
     * @Route("/my_settings", name="intake_my_settings")
     * @Template
     */
    public function settingsAction(Request $request)
    {
        $intakeUser = $this->getUser()->getExtendedUser();
        $form = $this->createForm(new UserContactSubscriptionType(), $intakeUser);
        $form->add('save', 'submit', array('label' => 'intake.user_settings.submit'));

        if ($request->getMethod() =="POST") {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($intakeUser);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'intake.message.user_settings_edit_success');
                return $this->redirect($this->generateUrl('intake_my_files'));
            }
            $this->get('session')->getFlashBag()->add('success', 'intake.message.user_settings_edit_error');
        }
        return array('form' => $form->createView());
    }
}