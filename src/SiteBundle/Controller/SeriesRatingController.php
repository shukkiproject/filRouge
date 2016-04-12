<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\SeriesRating;
use SiteBundle\Form\SeriesRatingType;

/**
 * SeriesRating controller.
 *
 * @Route("/seriesrating")
 */
class SeriesRatingController extends Controller
{
    /**
     * Lists all SeriesRating entities.
     *
     * @Route("/", name="seriesrating_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $seriesRatings = $em->getRepository('SiteBundle:SeriesRating')->findAll();

        return $this->render('seriesrating/index.html.twig', array(
            'seriesRatings' => $seriesRatings,
        ));
    }

    /**
     * Creates a new SeriesRating entity.
     *
     * @Route("/new", name="seriesrating_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $seriesRating = new SeriesRating();
        $form = $this->createForm('SiteBundle\Form\SeriesRatingType', $seriesRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seriesRating);
            $em->flush();

            return $this->redirectToRoute('seriesrating_show', array('id' => $seriesRating->getId()));
        }

        return $this->render('seriesrating/new.html.twig', array(
            'seriesRating' => $seriesRating,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SeriesRating entity.
     *
     * @Route("/{id}", name="seriesrating_show")
     * @Method("GET")
     */
    public function showAction(SeriesRating $seriesRating)
    {
        $deleteForm = $this->createDeleteForm($seriesRating);

        return $this->render('seriesrating/show.html.twig', array(
            'seriesRating' => $seriesRating,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SeriesRating entity.
     *
     * @Route("/{id}/edit", name="seriesrating_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SeriesRating $seriesRating)
    {
        $deleteForm = $this->createDeleteForm($seriesRating);
        $editForm = $this->createForm('SiteBundle\Form\SeriesRatingType', $seriesRating);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seriesRating);
            $em->flush();

            return $this->redirectToRoute('seriesrating_edit', array('id' => $seriesRating->getId()));
        }

        return $this->render('seriesrating/edit.html.twig', array(
            'seriesRating' => $seriesRating,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SeriesRating entity.
     *
     * @Route("/{id}", name="seriesrating_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SeriesRating $seriesRating)
    {
        $form = $this->createDeleteForm($seriesRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($seriesRating);
            $em->flush();
        }

        return $this->redirectToRoute('seriesrating_index');
    }

    /**
     * Creates a form to delete a SeriesRating entity.
     *
     * @param SeriesRating $seriesRating The SeriesRating entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SeriesRating $seriesRating)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('seriesrating_delete', array('id' => $seriesRating->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
