<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use SiteBundle\Entity\Series;
use SiteBundle\Form\SeriesType;
use SiteBundle\Entity\Comment;
use SiteBundle\Form\CommentType;
use SiteBundle\Entity\SeriesRating;
use SiteBundle\Entity\User;
use SiteBundle\Entity\Person;
use Symfony\Component\HttpFoundation\File\File;


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
    public function indexAction(Request $request)
    {
    


        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT s FROM SiteBundle:Series s";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );

        // parameters to template
        return $this->render('series/index.html.twig', array('pagination' => $pagination));
    }


    /**
     * Creates a new Series entity.
     *
     * @Route("/new", name="series_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {   
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup.');
        }
        $series = new Series();

        $form = $this->createForm('SiteBundle\Form\SeriesType', $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $series->setValidated(false);
            foreach ($series->getPersons() as $person) {
            
                $person->setValidated(false);
                $em->persist($person);
            } 

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
     * Finds and displays a Series entity.
     *
     * @Route("/{id}", name="series_show", requirements={
    *     "id": "\d+"} )
     * @Method({"GET", "POST"})
     */
    public function showAction(Series $series, Request $request)
    {
       

        $comment = new Comment($series);
        $form = $this->createForm('SiteBundle\Form\CommentType', $comment);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $average = $em->getRepository('SiteBundle:SeriesRating')->avgRatings($series->getId());
        $seriesRatings=number_format(floatval($average),1);

        return $this->render('series/show.html.twig', array(
            'series' => $series,
            'average' => $seriesRatings,
            'form' => $form->createView(),

        ));

    }

     /**
     * Validate an existing Series entity by moderator.
     *
     * @Route("/{id}/validate", requirements={
    *     "id": "\d+"}, name="series_validate")
     * @Method("GET")
     */
    public function validateAction(Series $series)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

        $em = $this->getDoctrine()->getManager();

        if (($series->getOldId())!==null) {
            $oldSeries = $em->getRepository('SiteBundle:Series')->find($series->getOldId());
            $oldSeries->setName($series->getName());
            $oldSeries->setSynopsisEn($series->getSynopsisEn());
            $oldSeries->setSynopsisFr($series->getSynopsisFr());
            $oldSeries->setYear($series->getYear());
            $oldSeries->setCreator($series->getCreator());
            $file = new File($series->getImageFile());
            $oldSeries->setImageFile($file);
            $oldSeries->setImageName($series->getImageName());
            $oldSeries->setValidated(true);
            $em->persist($oldSeries);

            $series->setImageName('');
            $em->remove($series); 
            $em->flush();

            return $this->redirectToRoute('moderator_index');
        }

        $series->setValidated(true);
        $em->persist($series);
        $em->flush();

        return $this->redirectToRoute('moderator_index');
    
    }

    /**
     * Displays a form to edit an existing Series entity.
     *
     * @Route("/{id}/edit", requirements={
    *     "id": "\d+"}, name="series_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Series $series)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup.');
        }

        $editForm = $this->createForm('SiteBundle\Form\SeriesType', $series);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
          //create a copy of te entity for validation
            $seriesCopy = new Series();
            $seriesCopy->setName($series->getName());
            $seriesCopy->setSynopsisEn($series->getSynopsisEn());
            $seriesCopy->setSynopsisFr($series->getSynopsisFr());
            $seriesCopy->setYear($series->getYear());
            $seriesCopy->setCreator($series->getCreator());
            $seriesCopy->setImageFile($series->getImageFile());
            $seriesCopy->setImageName($series->getImageName());
            $seriesCopy->setOldId($series->getId());
            $seriesCopy->setValidated(false);

            $em->detach($series);
            $em->persist($seriesCopy);

            foreach ($series->getPersons() as $person) {
                $personCopy= new Person();
                $personCopy->setLastname($person->getLastname());
                $personCopy->setFirstname($person->getFirstname());
                $personCopy->setCharacter($person->getCharacter());
                $personCopy->setOldId($person->getId());
                $personCopy->setValidated(false);
                $em->detach($person);
                $em->persist($personCopy);
                
            }
            $em->flush();

            return $this->redirectToRoute('series_show', array('id' => $series->getId()));

        }

        return $this->render('series/edit.html.twig', array(
            'series' => $series,
            'edit_form' => $editForm->createView(),
        ));
    }

 //this version create double entry
            // $seriesC = clone $series;
            // $seriesC->setOldId($series->getId());
            // $seriesC->setValidated(false);
            // foreach ($seriesC->getPersons() as $person) {
            //     $personC= new Person();
            //     $personC = clone $person;
            //     $personC->setOldId($person->getId());
            //     $personC->setValidated(false);
            //     $series->addPerson($person);
            //     $seriesC->removePerson($person);
            //     $series->removePerson($personC);
            //     $em->detach($person);
            //     $em->persist($personC);
            //     // var_dump($personC->getSeries());
            //     // die;
            // } 
            // $em->detach($series);
            // $em->persist($seriesC);

            // $em->flush();

    /**
     * Follow a series.
     *
     * @Route("/{id}/follow", requirements={
    *     "id": "\d+"}, name="series_follow")
     * @Method("GET")
     */
    public function followAction(Series $series, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup to follow the series.');
        }
        $user = $this->getUser();
        if ($user->getSeriesFollowed()->contains($series)) {
            $user->removeSeriesFollowed($series);
        } else {
            $user->addSeriesFollowed($series);  
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->isfollowedAction($series, $request);
    }

    /**
     * Check whetehr a series is followed.
     *
     * @Route("/{id}/isfollowed", requirements={
    *     "id": "\d+"}, name="series_isfollowed")
     * @Method("GET")
     */
    public function isFollowedAction(Series $series, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup to follow the series.');
        }
        $user = $this->getUser();
        $locale = $request->getLocale(); 

        $response = new JsonResponse();
        if ($user->getSeriesFollowed()->contains($series)) {
            $status = ($locale==='en')? 'Unfollow' : 'Ne pas suivre';
            $response->setData(array('status' => $status));
        } else {
            $status = ($locale==='en')? 'Follow' : 'Suivre';
            $response->setData(array('status' => $status));
        }
        return $response;
    }

    /**
     * Deletes a Series entity.
     *
     * @Route("/{id}/delete", requirements={
    *     "id": "\d+"}, name="series_delete")
     * @Method("GET")
     */
    public function deleteAction(Series $series)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');
        
            $em = $this->getDoctrine()->getManager();
            $em->remove($series);
            $em->flush();

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
