<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactForm;

/**
 * @Route("/contacts")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ContactController extends Controller
{
    /**
     * @Route("/", name="intake_contacts")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Contact')->findActive(0, true);
        $paginator = $this->get('knp_paginator');
        $contacts = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $request->query->get('results', 10)
        );

        return ['contacts' => $contacts ];
    }

    /**
     * @Route("/new", name="intake_contact_new")
     * @Template()
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);

        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'intake.contact.new_submit_button',
                'attr' => ['class' => 'btn btn-primary']
            ]
        );

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'intake.success_create_new_contact');
                return $this->redirect($this->generateUrl('intake_contacts'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'intake.error_create_new_contact');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/show/{contact}", name="intake_contact_show")
     * @Template()
     * @Method({"GET"})
     */
    public function showAction(Contact $contact)
    {
        return ['contact' => $contact];
    }

    /**
     * @Route("/edit/{contact}", name="intake_contact_edit")
     * @Template()
     * @Method({"GET", "PUT", "POST"})
     */
    public function editAction(Request $request, Contact $contact)
    {
        $form = $this->createForm(ContactForm::class, $contact);

        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'intake.contact.edit_submit_button',
                'attr' => ['class' => 'btn btn-primary']
            ]
        )->add(
            'delete',
            SubmitType::class,
            [
                'label' => 'intake.contact.delete_submit_button',
                'attr' => ['class' => 'btn btn-link']
            ]
        );

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->isClicked('submit')) {
                    $em->persist($contact);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'intake.success_update_contact');
                }
                if ($form->isClicked('delete')) {
                    $em->remove($contact);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('info', 'intake.success_delete_contact');
                }

                return $this->redirect($this->generateUrl('intake_contacts'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'intake.error_create_new_contact');
            }
        }

        return ['form' => $form->createView()];
    }
}
