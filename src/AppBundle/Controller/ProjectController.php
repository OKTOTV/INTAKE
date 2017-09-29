<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Project;
use AppBundle\Form\projectForm;

/**
 * @Route("/projects")
 * @Security("has_role('ROLE_USER')")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/", name="intake_projects")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Project')->findActive(0, true);
        $paginator = $this->get('knp_paginator');
        $projects = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $request->query->get('results', 10)
        );

        return ['projects' => $projects ];
    }

    /**
     * @Route("/show/{project}", name="intake_project_show")
     * @Template()
     * @Method({"GET"})
     */
    public function showAction(project $project)
    {
        return ['project' => $project];
    }


    /**
     * @Route("/delete/{project}", name="intake_project_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        $this->get('session')->getFlashBag()->add('info', 'intake.success_delete_project');

        return $this->redirect($this->generateUrl('intake_projects'));
    }
}
