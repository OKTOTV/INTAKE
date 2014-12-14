<?php

namespace Oktolab\IntakeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oktolab\IntakeBundle\Entity\User;

/**
 * @Route("/backend/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="intake_backend_users")
     * @Template
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:User')->findAll();
        return array('users' => $users);
    }

    /**
     * @Route("/{contact}/edit", name="intake_backend_show_user")
     * @Template
     */
    public function edit_userAction(Request $request, User $user)
    {
        $form = $this->createForm(new UserType(), $user);
        $form->add('save', 'submit', array('label' => 'intake.edit_user.submit'));

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);

            if ($form->isValid()) { 
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "intake.message.user_edit_success");
                return $this->redirect($this->generateUrl('intake_backend_users'));
            
            }
            $this->get('session')->getFlashBag()->add('error', "intake.message.user_edit_error"); 
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/{user}/delete", name="intake_backend_user_delete")
     * @Template()
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'intake.message.user_delete_success');
        $this->redirect($this->generateUrl('intake_backend_users'));
    }
}