<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use SiteBundle\Entity\Episode;
use SiteBundle\Form\EpisodeType;
use SiteBundle\Entity\User;

/**
 * Episode controller.
 *
 * @Route("/episode")
 */
class EpisodeController extends Controller
{
    /**
     * Lists all Episode entities.
     *
     * @Route("/", name="episode_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $episodes = $em->getRepository('SiteBundle:Episode')->findAll();

        return $this->render('episode/index.html.twig', array(
            'episodes' => $episodes,
        ));
    }

    /**
     * Creates a new Episode entity.
     *
     * @Route("/new", name="episode_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $episode = new Episode();
        $form = $this->createForm('SiteBundle\Form\EpisodeType', $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($episode);
            $em->flush();

            return $this->redirectToRoute('episode_show', array('id' => $episode->getId()));
        }

        return $this->render('episode/new.html.twig', array(
            'episode' => $episode,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Episode entity.
     *
     * @Route("/{id}", name="episode_show")
     * @Method("GET")
     */
    public function showAction(Episode $episode)
    {
        $deleteForm = $this->createDeleteForm($episode);

        return $this->render('episode/show.html.twig', array(
            'episode' => $episode,
            'delete_form' => $deleteForm->createView(),
        ));
    }

     /**
     * Validate an existing Episode entity by moderator.
     *
     * @Route("/{id}/validate", requirements={
    *     "id": "\d+"}, name="episode_validate")
     * @Method("GET")
     */
    public function validateAction(Episode $episode)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

        $em = $this->getDoctrine()->getManager();

        if (($episode->getOldId())!==null) {
                $oldEpisode = $em->getRepository('SiteBundle:Episode')->find($episode->getOldId());
                if (isset($oldEpisode)) {

                    $oldEpisode->setEpisode($episode->getEpisode());
                    $oldEpisode->setTitle($episode->getTitle());
                    $oldEpisode->setSynopsisEn($episode->getSynopsisEn());
                    $oldEpisode->setSynopsisFr($episode->getSynopsisFr());
                    $oldEpisode->setValidated(true);

                    $em->persist($oldepisode);
                    $em->remove($episode);
                    $em->flush();
                    return $this->redirectToRoute('moderator_index');
                }
       
            }

        $episode->setValidated(true);
        $em->persist($episode);
        $em->flush();

        return $this->redirectToRoute('moderator_index');
    
    }

    /**
     * Displays a form to edit an existing Episode entity.
     *
     * @Route("/{id}/edit", name="episode_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Episode $episode)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');
        $deleteForm = $this->createDeleteForm($episode);
        $editForm = $this->createForm('SiteBundle\Form\EpisodeType', $episode);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($episode);
            $em->flush();

            return $this->redirectToRoute('episode_edit', array('id' => $episode->getId()));
        }

        return $this->render('episode/edit.html.twig', array(
            'episode' => $episode,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Episode entity.
     *
     * @Route("/{id}/delete", name="episode_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Episode $episode)
    {
            $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');
            $em = $this->getDoctrine()->getManager();
            $series=$episode->getSeason()->getSeries();
            $em->remove($episode);
            $em->flush();

        return $this->redirectToRoute('series_show', array('id' => $series->getId()));
    }

    /**
     * update if user has watched an episode
     *
     * @Route("/{id}/watch", name="episode_watch")
     * @Method("GET")
     */
    public function watchAction(Episode $episode)
    {
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException('Please login or signup.');
            }
            $user = $this->getUser();
            if (!$user->getEpisodesWatched()->contains($episode)) {
                $user->addEpisodesWatched($episode);
            } else {
                $user->removeEpisodesWatched($episode);
            }
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

            $series=$episode->getSeason()->getSeries();

        return $this->redirectToRoute('series_show', array('id' => $series->getId()));
    }

    /**
     * Check whetehr an episode is watched.
     *
     * @Route("/{id}/iswatched", name="episode_iswatched")
     * @Method("GET")
     */
    public function isWatchedAction(Episode $episode)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup.');
        }
        $user = $this->getUser();
        $response = new JsonResponse();

        if ($user->getEpisodesWatched()->contains($episode)) {
            $response->setData(array('watched' => true));
        } else {
            $response->setData(array('watched' => false));
        }
            return $response;
    }

    /**
     * Creates a form to delete a Episode entity.
     *
     * @param Episode $episode The Episode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Episode $episode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('episode_delete', array('id' => $episode->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
