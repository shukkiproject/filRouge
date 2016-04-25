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
use SiteBundle\Entity\User;


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
        
            return $this->redirectToRoute('series_index');
        }

        return $this->render('series/new.html.twig', array(
            'series' => $series,
            'form' => $form->createView(),
        ));
    }

     /**
     * Propose changes to a Series entity.
     *
     * @Route("/{id}/proposechanges", defaults={"id": 0}, name="propose_changes")
     * @Method({"GET", "POST"})
     */
    public function proposeChangesAction(Request $request,Series $series, $id)
    {
        
        $form = $this->createForm('SiteBundle\Form\SeriesType', $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $seriesC = new Series();
            $seriesC->setOldId($series->getId());
            $seriesC->setName($series->getName());
            $seriesC->setSynopsis($series->getSynopsis());
            $seriesC->setPoster($series->getPoster());
            $seriesC->setPersons($series->getPersons());
            $seriesC->setValidated(false);

            $em = $this->getDoctrine()->getManager();
            $em->detach($series);
            $em->persist($seriesC);
            $em->flush();

            return $this->redirectToRoute('series_show', array('id' => $id));
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

        $form=CommentController::newFormAction($series, $request);
        $average=SeriesRatingController::indexAction($series->getId());
        // var_dump($form);
        // die;
        return $this->render('series/show.html.twig', array(
            'series' => $series,
            'average' => $average,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView(),

        ));

    }

     /**
     * Validate an existing Series entity by admin.
     *
     * @Route("/{id}/validate", name="series_validate")
     * @Method("GET")
     */
    public function validateAction(Series $series)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this admin page!');

        $em = $this->getDoctrine()->getManager();

        if (($series->getOldId())!==null) {
            $oldSeries = $em->getRepository('SiteBundle:Series')->find($series->getOldId());
            $oldSeries->setName($series->getName());
            $oldSeries->setSynopsis($series->getSynopsis());
            $oldSeries->setPoster($series->getPoster());
            $oldSeries->setPersons($series->getPersons());
            $oldSeries->setValidated(true);
            $em->persist($oldSeries);
            $em->remove($series);
            $em->flush();

            return $this->redirectToRoute('site_main_admin');
        }

        $series->setValidated(true);
        $em->persist($series);
        $em->flush();

        return $this->redirectToRoute('site_main_admin');
    
    }

    /**
     * Displays a form to edit an existing Series entity.
     *
     * @Route("/{id}/edit", name="series_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Series $series)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this admin page!');

        $editForm = $this->createForm('SiteBundle\Form\SeriesType', $series);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->validateAction($series);
        }

        return $this->render('series/edit.html.twig', array(
            'series' => $series,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Follow a series.
     *
     * @Route("/{id}/follow", name="series_follow")
     * @Method("GET")
     */
    public function followAction(Series $series)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup to follow the series.');
        }
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $user->addSeriesFollowed($series);
        $em->persist($user);
        $em->flush();
        //TODO : WARNING IF ALREADY FOLLOWED
        // if (!$em->persist($user)->flush()) {
        //     throw new HttpException(404, "Whoops! Looks like you\'ve already followed the series!");
        // }
 
        return $this->redirectToRoute('series_show', array('id' => $series->getId()));
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
