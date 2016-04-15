<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\SeriesRating;
use SiteBundle\Form\SeriesRatingType;
use SiteBundle\Entity\Series;
use SiteBundle\Form\SeriesType;

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
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $average = $em->getRepository('SiteBundle:SeriesRating')->avgRatings($id);
        return $seriesRatings=intval($average);

    }

    /**
     * Creates a new SeriesRating entity.
     *
     * @Route("/new", name="seriesrating_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Series $series, Request $request)
    {
        $seriesRating = new SeriesRating($series);
        $ratingForm = $this->createForm('SiteBundle\Form\SeriesRatingType', $seriesRating);
        $ratingForm->handleRequest($request);

        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {

             if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException('Please login or signup to rate the series.');
            }
            $user = $this->getUser();
            
            $em = $this->getDoctrine()->getManager();
            $prefExist = $em->getRepository('SiteBundle:SeriesRating')->findOneBy(array('user' => $user, 'series' => $series));

            if (!isset($prefExist)) {
                $seriesRating->setUser($user)
                            ->setSeries($series);
                $em->persist($seriesRating);
                $em->flush();
            } else {

                if(($prefExist->getRatings())!==($seriesRating->getRatings())){
                        $prefExist->setRatings($seriesRating->getRatings());
                        $em->persist($prefExist);
                        $em->flush();
                } else {
                        //FLASHBAG DOESN'T WORK!!!!!!!!!!!!!!!!!!!!
                        $this->addFlash('alert', 'You\'ve already given this series the same ratings!');
                        // return $this->redirectToRoute('series_show', array('id' => $seriesId));
                }
            }

            return $this->redirectToRoute('series_show', array('id' => $series->getId()));
        }

        return $ratingForm;
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
