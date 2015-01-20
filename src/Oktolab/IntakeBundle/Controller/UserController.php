<?php

namespace Oktolab\IntakeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oktolab\IntakeBundle\Entity\User;
use Oktolab\IntakeBundle\Form\UserType;

/**
 * @Route("/backend/admin/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="intake_backend_users")
     * @Template
     */
    public function list_usersAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:User')->findAll();
        return array('users' => $users);
    }

    /**
     * @Route("/new", name="intake_backend_user_new")
     * @Template
     */
    public function new_userAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $form->add('save', 'submit', array('label' => 'intake.new_user.submit'));

        if ($request->getMethod() == "POST") { //form send
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'intake.message.user_create_success');
                return $this->redirect($this->generateUrl('intake_backend_users'));
            }
            $this->get('session')->getFlashBag()->add('error', 'intake.message.user_create_error');
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/{user}/edit", name="intake_backend_user_edit")
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
                $em->persist($user);
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
     * @Template
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