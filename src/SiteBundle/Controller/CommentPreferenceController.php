<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use SiteBundle\Entity\CommentPreference;
use SiteBundle\Form\CommentPreferenceType;
use SiteBundle\Entity\Comment;

/**
 * CommentPreference controller.
 *
 * @Route("/commentpreference")
 */
class CommentPreferenceController extends Controller
{
    /**
     * Lists all CommentPreference entities.
     *
     * @Route("/", name="commentpreference_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentPreferences = $em->getRepository('SiteBundle:CommentPreference')->findAll();

        return $this->render('commentpreference/index.html.twig', array(
            'commentPreferences' => $commentPreferences,
        ));
    }

    /**
     * Creates a new CommentPreference entity.
     *
     * @Route("/new", name="commentpreference_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $commentPreference = new CommentPreference();
        $form = $this->createForm('SiteBundle\Form\CommentPreferenceType', $commentPreference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentPreference);
            $em->flush();

            return $this->redirectToRoute('commentpreference_show', array('id' => $commentPreference->getId()));
        }

        return $this->render('commentpreference/new.html.twig', array(
            'commentPreference' => $commentPreference,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CommentPreference entity.
     *
     * @Route("/{id}", name="commentpreference_show")
     * @Method("GET")
     */
    public function showAction(CommentPreference $commentPreference)
    {
        $deleteForm = $this->createDeleteForm($commentPreference);

        return $this->render('commentpreference/show.html.twig', array(
            'commentPreference' => $commentPreference,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CommentPreference entity.
     *
     * @Route("/{id}/edit", name="commentpreference_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CommentPreference $commentPreference)
    {
        $deleteForm = $this->createDeleteForm($commentPreference);
        $editForm = $this->createForm('SiteBundle\Form\CommentPreferenceType', $commentPreference);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentPreference);
            $em->flush();

            return $this->redirectToRoute('commentpreference_edit', array('id' => $commentPreference->getId()));
        }

        return $this->render('commentpreference/edit.html.twig', array(
            'commentPreference' => $commentPreference,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CommentPreference entity.
     *
     * @Route("/{id}", name="commentpreference_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CommentPreference $commentPreference)
    {
        $form = $this->createDeleteForm($commentPreference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentPreference);
            $em->flush();
        }

        return $this->redirectToRoute('commentpreference_index');
    }
 /**
     * Create a CommentPreference entity.
     *
     * @Route("/{pref}/comment/{id}", name="commentpreference_setpref")
     * @Method("GET")
     */
    public function preferenceAction($pref, Comment $comment)
    {

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup.');
        }
        $user = $this->getUser();

        //TEST IF EXIST ALREADY IN DB, SAME USER, SAME COMMENT
        //IF EXIST: JUST CHANGE THE PREF, ELSE NEW OBJECT
        $em = $this->getDoctrine()->getManager();
        $prefExist = $em->getRepository('SiteBundle:CommentPreference')->findOneBy(array('user' => $user, 'comment' => $comment));
        if (!isset($prefExist)) {
            $commentPreference = new CommentPreference();
            $commentPreference->setUser($user)
                ->setComment($comment)   
                ->setLiked($pref);
            $em->persist($commentPreference);
            $em->flush();

        } elseif($prefExist!==$pref){
            $prefExist->setLiked($pref);
            $em->persist($prefExist);
            $em->flush();
        }
        return $this->isLikedAction($comment);
    }

    /**
     * Count number of likes/dislikes of a comment.
     *
     * @Route("/comment/{id}", name="commentpreference_isliked")
     * @Method("GET")
     */
    public function isLikedAction(Comment $comment)
    {
        //find by comment id , count likes and dislikes
        $em = $this->getDoctrine()->getManager();
        $likes = $em->getRepository('SiteBundle:CommentPreference')->findBy(array('comment' => $comment, 'liked' => 1));
        $dislikes = $em->getRepository('SiteBundle:CommentPreference')->findBy(array('comment' => $comment, 'liked' => 0));

        $response = new JsonResponse();
        return $response->setData(array('like' => count($likes), 'dislike' => count($dislikes)));
    }

    /**
     * Creates a form to delete a CommentPreference entity.
     *
     * @param CommentPreference $commentPreference The CommentPreference entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CommentPreference $commentPreference)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentpreference_delete', array('id' => $commentPreference->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
