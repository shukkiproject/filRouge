<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Comment;
use SiteBundle\Form\CommentType;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all Comment entities.
     *
     * @Route("/", name="comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('SiteBundle:Comment')->findAll();

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

    /**
     * Creates a new Comment entity.
     *
     * @Route("/new", name="comment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        // // var_dump($request);
        // // die;
        
        // $comment = new Comment($series);
        // $form = $this->createForm('SiteBundle\Form\CommentType', $comment);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

            //test if it's an object user/ logged in, then redirect to login
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException('Please login or signup to leave a comment.');
            }
            $user = $this->getUser();
            $comment->setUser($user);
            // var_dump($user);
            // die;

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('series_show', array('id' => $series->getId()));
        // }

        // return $this->render('comment/new.html.twig', array(
        //     'comment' => $comment,
        //     'form' => $form->createView(),
        // ));
    }

    /**
     * Finds and displays a Comment entity.
     *
     * @Route("/{id}", name="comment_show")
     * @Method("GET")
     */
    public function showAction(Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);

        return $this->render('comment/show.html.twig', array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);
        $editForm = $this->createForm('SiteBundle\Form\CommentType', $comment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('comment_edit', array('id' => $comment->getId()));
        }

        return $this->render('comment/edit.html.twig', array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}/delete", name="comment_delete")
     * @Method("GET")
     */
    public function deleteAction(Comment $comment)
    {
      
            $em = $this->getDoctrine()->getManager();
            $series=$comment->getSeries();

            $em->remove($comment);
            $em->flush();
      
        return $this->redirectToRoute('series_show', array('id' => $series->getId()));
    }

    /**
     * Creates a form to delete a Comment entity.
     *
     * @param Comment $comment The Comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
