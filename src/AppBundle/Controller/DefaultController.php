<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectForm::class, $project);

        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'intake.index_submit_button',
                'attr' => ['class' => 'btn btn-primary']
            ]
        );

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'intake.success_submit_project');
                return $this->redirect($this->generateUrl('thank_you'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'intake.error_submit_project');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }
}
