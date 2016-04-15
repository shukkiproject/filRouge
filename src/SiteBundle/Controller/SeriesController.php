<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Controller\CommentController;
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

        //show all the comments of the series
        $em = $this->getDoctrine()->getManager();
        // $comments = $em->getRepository('SiteBundle:Series')->showDetails($series->getId());
        
        // $response = $this->forward('SiteBundle:Comment:new', array('comment'=> $comment, 'form' => $form));
        // return $response; 

        // show the new comment input form
        $comment = new Comment($series);
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);

        //TODO TRY TO MOVE THIS PART BACK TO THE COMMENT CONTROLLER !!!
        if ($form->isSubmitted() && $form->isValid()) {
        
        //test if it's an object user/ logged in, then redirect to login
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException('Please login or signup to leave a comment.');
            }
            $user = $this->getUser();
            $comment->setUser($user);
            // var_dump($user);
            // die;
            $em2 = $this->getDoctrine()->getManager();
            $em2->persist($comment);
            $em2->flush();

            return $this->redirectToRoute('series_show', array('id' => $series->getId()));
        }

        return $this->render('series/show.html.twig', array(
            'series' => $series,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),

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
