<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Season;
use SiteBundle\Form\SeasonType;

/**
 * Season controller.
 *
 * @Route("/season")
 */
class SeasonController extends Controller
{
    /**
     * Lists all Season entities.
     *
     * @Route("/", name="season_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $seasons = $em->getRepository('SiteBundle:Season')->findAll();

        return $this->render('season/index.html.twig', array(
            'seasons' => $seasons,
        ));
    }

    /**
     * Creates a new Season entity.
     *
     * @Route("/new", name="season_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $season = new Season();
        $form = $this->createForm('SiteBundle\Form\SeasonType', $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($season);
            $em->flush();

            return $this->redirectToRoute('season_show', array('id' => $season->getId()));
        }

        return $this->render('season/new.html.twig', array(
            'season' => $season,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Season entity.
     *
     * @Route("/{id}", name="season_show")
     * @Method("GET")
     */
    public function showAction(Season $season)
    {
        $deleteForm = $this->createDeleteForm($season);

        return $this->render('season/show.html.twig', array(
            'season' => $season,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Season entity.
     *
     * @Route("/{id}/edit", name="season_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Season $season)
    {
        $deleteForm = $this->createDeleteForm($season);
        $editForm = $this->createForm('SiteBundle\Form\SeasonType', $season);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($season);
            $em->flush();

            return $this->redirectToRoute('season_edit', array('id' => $season->getId()));
        }

        return $this->render('season/edit.html.twig', array(
            'season' => $season,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Season entity.
     *
     * @Route("/{id}", name="season_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Season $season)
    {
        $form = $this->createDeleteForm($season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($season);
            $em->flush();
        }

        return $this->redirectToRoute('season_index');
    }

    /**
     * Creates a form to delete a Season entity.
     *
     * @param Season $season The Season entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Season $season)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('season_delete', array('id' => $season->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
