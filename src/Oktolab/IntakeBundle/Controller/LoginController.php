<?php

namespace Oktolab\IntakeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="intake_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return array('last_username' => $lastUsername, 'error' => $error);
    }

    /**
     * @Route("/backend/login_check", name="intake_login_check")
     */
    public function login_checkAction()
    {
         return $this->redirect($this->generateUrl('intake_backend'));
    }

    /**
     * @Route("/iforgot", name="intake_iforgot")
     * @Template
     */
    public function reset_password_mailAction(Request $request)
    {
        // TODO: form for username, set resetHash, send mail to password form
         $form = $this->createFormBuilder()
            ->add(
                'username', 
                'text', 
                array(
                    'label' => 'intake.iforgot.username', 
                    'constraints' => array(
                        new NotBlank(), 
                        new Length(array('max' => 255))
                    )
                )
            )
            ->add('save', 'submit', array('label' => 'intake.iforgot.reset_password_submit'))
            ->getForm();

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->get('oktolab.iforgot')->resetPassword($data['username']);

                $this->get('session')->getFlashBag()->add('info', 'intake.message.iforgot_success');
                return $this->redirect($this->generateUrl('intake_new'));
            } 
            $this->get('session')->getFlashBag()->add('error', 'intake.message.iforgot_error');
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/iforgot/{resetHash}/new_password", name="intake_iforgot_reset")
     * @Template
     */
    public function reset_passwordAction(Request $request, $resetHash)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('OktolabIntakeBundle:User')->findOneBy(array('resetHash' => $resetHash));
        if (!$user) {
            return $this->redirect($this->generateUrl('intake_new'));
        }

        $form = $this->createFormBuilder()
            ->add(
                'password',
                'password',
                array(
                    'label' => 'intake.iforgot.password',
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array('min' => 6))
                    )
                )
            )
            ->add('save', 'submit', array('label' => 'intake.iforgot.save_password'))
            ->getForm();

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->get('oktolab.iforgot')->setPassword($data['password'], $resetHash);

                $this->get('session')->getFlashBag()->add('success', 'intake.message.iforgot_reset_success');
                return $this->redirect($this->generateUrl('intake_login'));
            }
            $this->get('session')->getFlashBag()->add('error', 'intake.message.iforgot_reset_error');
        }
        return array('form' => $form->createView());
    }
}