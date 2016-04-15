<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Controller\CommentController;
use SiteBundle\Controller\SeriesRatingController;
use SiteBundle\Entity\Series;
use SiteBundle\Form\SeriesType;
use SiteBundle\Entity\Comment;
use SiteBundle\Form\CommentType;


/**
 * Series controller.
 *
 * @Route("/series")
 */
class SeriesController extends Controller
{
    /**
     * Lists all Series entities.
     *
     * @Route("/", name="series_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $series = $em->getRepository('SiteBundle:Series')->findByValidated(true);
        
        return $this->render('series/index.html.twig', array(
            'series' => $series,
        ));
    }

    /**
     * Creates a new Series entity.
     *
     * @Route("/new", name="series_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $series = new Series();
        $form = $this->createForm('SiteBundle\Form\SeriesType', $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $series->setValidated(false);
            $em->persist($series);
            $em->flush();

            return $this->redirectToRoute('series_show', array('id' => $series->getId()));
        }

        return $this->render('series/new.html.twig', array(
            'series' => $series,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Series entity.
     *
     * @Route("/{id}", name="series_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Series $series, Request $request)
    {

        $deleteForm = $this->createDeleteForm($series);

        $form=CommentController::newAction($series, $request);
        $average=SeriesRatingController::indexAction($series->getId());
        $ratingForm=SeriesRatingController::newAction($series, $request);

        return $this->render('series/show.html.twig', array(
            'series' => $series,
            'average' => $average,
            'delete_form' => $deleteForm->createView(),
            'ratingForm' => $ratingForm->createView(),
            'form' => $form->createView(),

        ));

    }

    /**
     * Displays a form to edit an existing Series entity.
     *
     * @Route("/{id}/edit", name="series_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Series $series)
    {
        $deleteForm = $this->createDeleteForm($series);
        $editForm = $this->createForm('SiteBundle\Form\SeriesType', $series);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($series);
            $em->flush();

            return $this->redirectToRoute('series_edit', array('id' => $series->getId()));
        }

        return $this->render('series/edit.html.twig', array(
            'series' => $series,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Series entity.
     *
     * @Route("/{id}", name="series_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Series $series)
    {
        $form = $this->createDeleteForm($series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($series);
            $em->flush();
        }

        return $this->redirectToRoute('series_index');
    }

    /**
     * Creates a form to delete a Series entity.
     *
     * @param Series $series The Series entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Series $series)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('series_delete', array('id' => $series->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
