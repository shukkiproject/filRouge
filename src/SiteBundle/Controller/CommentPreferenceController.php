<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use SiteBundle\Entity\CommentPreference;
use SiteBundle\Form\CommentPreferenceType;

/**
 * CommentPreference controller.
 *
 * @Route("/")
 */
class CommentPreferenceController extends Controller
{
    /**
     * Lists all CommentPreference entities.
     *
     * @Route("/commentpreference/", name="commentpreference_index")
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
     * @Route("/commentpreference/new", name="commentpreference_new")
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
     * @Route("/commentpreference/{id}", name="commentpreference_show")
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
     * @Route("/commentpreference/{id}/edit", name="commentpreference_edit")
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
     * @Route("/commentpreference/{id}", name="commentpreference_delete")
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
     * @Route("series/{seriesId}/comment/{commentId}/preference/{pref}", name="commentpreference_pref")
     * @Method("GET")
     */
    public function preferenceAction($seriesId, $commentId, $pref)
    {
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException('Please login or signup.');
            }
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository('SiteBundle:Comment')->find($commentId);
            //TEST IF EXIST ALREADY IN DB, SAME USER, SAME COMMENT
            //IF EXIST JUST CHANGE THE PREF, ELSE NEW OBJECT
            $prefExist = $em->getRepository('SiteBundle:CommentPreference')->findOneBy(array('user' => $user, 'comment' => $comment));
            if (!isset($prefExist)) {
                $commentPreference = new CommentPreference();
                $commentPreference->setUser($user)
                    ->setComment($comment)   
                    ->setLiked($pref);
                $em->persist($commentPreference);
                $em->flush();

            } else{
                $prefExist->setLiked($pref);
                $em->persist($prefExist);
                $em->flush();
            }
           

        return $this->redirectToRoute('series_show', array('id' => $seriesId));
    }

    /**
     * Check whetehr a comment is liked/disliked.
     *
     * @Route("series/{seriesId}/comment/{commentId}", name="commentpreference_isliked")
     * @Method("GET")
     */
    public function isLikedAction($seriesId, $commentId)
    {
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException('Please login or signup.');
            }
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository('SiteBundle:Comment')->find($commentId);
            //TEST IF EXIST ALREADY IN DB, SAME USER, SAME COMMENT
            //IF EXIST JUST CHANGE THE PREF, ELSE NEW OBJECT
            $prefExist = $em->getRepository('SiteBundle:CommentPreference')->findOneBy(array('user' => $user, 'comment' => $comment));
            if (!isset($prefExist)) {
                return new JsonResponse(false);
            } else{
                if ($prefExist->getLiked()) {
                    return new JsonResponse('Liked');
                } else {
                    return new JsonResponse('Disliked');
                }
            }
           

        return $this->redirectToRoute('series_show', array('id' => $seriesId));
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
