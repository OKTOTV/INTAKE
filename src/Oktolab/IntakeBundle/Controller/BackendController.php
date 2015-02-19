<?php

namespace Oktolab\IntakeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oktolab\IntakeBundle\Entity\File;
use Oktolab\IntakeBundle\Entity\Contact;
use Oktolab\IntakeBundle\Form\ContactType;
use Oktolab\IntakeBundle\Form\UserSettingsType;

/**
 * @Route("/backend")
 */
class BackendController extends Controller
{
    /**
     * Show a nice backend with all currently submitted files. 
     *
     * @Route("/", name="intake_backend")
     * @Template
     */
    public function list_filesAction()
    {
        $files = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:File')->findAll();

        return array('files' => $files);
    }

    /**
     * Delete Files in the backend
     * @Route("/delete/{file}", name="intake_backend_delete")
     */
    public function delete(File $file)
    {
        $this->get('oktolab.upload_listener')->deleteFile($file);
        $this->get('session')->getFlashBag()->add('success', 'intake.message.file_delete_success');
        return $this->redirect($this->generateUrl('intake_backend'));
    }

    /**
     * List all contacts
     * @Route("/contacts", name="intake_backend_contacts")
     * @Template
     */
    public function list_contactsAction()
    {
        $contacts = $this->getDoctrine()->getManager()->getRepository('OktolabIntakeBundle:Contact')->findAll();
        return array ('contacts' => $contacts);
    }

    /**
     * Create new contact
     * @Route("/contact/new", name="intake_backend_contact_new")
     * @Template
     */
    public function new_contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(new ContactType(), $contact);
        $form->add('save', 'submit', array('label' => 'intake.new_contact.submit'));

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);

            if ($form->isValid()) { 
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "intake.message.contact_create_success");
                return $this->redirect($this->generateUrl('intake_backend_contacts'));
            
            }
            $this->get('session')->getFlashBag()->add('error', "intake.message.contact_create_error"); 
        }
        return array('form' => $form->createView());
    }

    /**
     * Edit contact
     * @Route("/contact/{contact}/edit", name="intake_backend_contact_edit")
     * @Template
     */
    public function edit_contactAction(Request $request, Contact $contact)
    {
        $form = $this->createForm(new ContactType(), $contact);
        $form->add('save', 'submit', array('label' => 'intake.edit_contact.submit'));

        if ($request->getMethod() == "POST") { //form sent
            $form->handleRequest($request);

            if ($form->isValid()) { 
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "intake.message.contact_edit_success");
                return $this->redirect($this->generateUrl('intake_backend_contacts'));
            
            }
            $this->get('session')->getFlashBag()->add('error', "intake.message.contact_edit_error"); 
        }
        return array('form' => $form->createView());
    }

    /**
     * Delete Files in the backend
     * @Route("/contact/{contact}/delete", name="intake_backend_contact_delete")
     */
    public function deleteContact(Contact $contact)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'intake.message.contact_delete_success');
        return $this->redirect($this->generateUrl('intake_backend_contacts'));
    }

    /**
     * Allow users to change their settings. (Email, password)
     * TODO: 
     * 
     * @Route("/user/{user}/edit", name="intake_backend_user_change_settings")
     * @Template
     */
    public function changeSettings(Request $request, User $user)
    {
        // only admins and the user himself can change his settings
        if (in_array('ROLE_ADMIN', $user->getRoles()) || $user == $this->getUser()) {

            $form = $this->createForm(new UserSettingsType(), $user);
            $form->add('save', 'submit', array('label' => 'intake.edit_user.submit'));

            if ($request->getMethod() == "POST") { //form sent
                $form->handleRequest($request);

                if ($form->isValid()) {

                }
                $this->get('session')->getFlashBag()->add('error', 'intake.message.user_edit_error');
            }
            return array('form' => $form->createView());
        }

        $this->get('session')->getFlashBag()->add('error', 'intake.message.user_not_allowed');
        return $this->redirect($this->generateUrl('intake_backend_users'));
            
    }

    /**
     * Allows sorting of contacts
     * @Route("/contacts/sort", name="intake_backend_contact_sort")
     */
    public function sortContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $params = json_decode($request->getContent(), true);

        foreach ($params as $key => $value) {
            $contact = $em->getRepository('OktolabIntakeBundle:Contact')->findOneBy(array('id' => $key));
            $contact->setOrder($value);
            $em->persist($contact);
        }
        $em->flush();
        return new Response(null, 200);
    }
}