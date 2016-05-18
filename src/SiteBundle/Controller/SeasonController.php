<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Season;
use SiteBundle\Form\SeasonType;
use SiteBundle\Entity\Episode;
use SiteBundle\Entity\Series;

/**
 * Season controller.
 *
 * @Route("/")
 */
class SeasonController extends Controller
{
    /**
     * Lists all Season entities.
     *
     * @Route("season/", name="season_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $seasons = $em->getRepository('SiteBundle:Season')->findByValidated(true);

        return $this->render('season/index.html.twig', array(
            'seasons' => $seasons,
        ));
    }

    /**
     * Creates a new Season entity.
     *
     * @Route("series/{id}/season-episode/new", name="season_episode_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Series $series)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) { throw $this->createAccessDeniedException('Please login or signup.');}

        $season = new Season();
        $season->setSeries($series);
        $form = $this->createForm('SiteBundle\Form\SeasonType', $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //collect all the seasons to check if the season number exist aleady
            $seasons=$season->getSeries()->getSeasons();
            $tab=[];
            foreach ($seasons as $oldSeason) {
                $seasonNo=$oldSeason->getSeason();
                $tab[]=$seasonNo;
            }
            //add only if it's not existed
            if (!in_array($season->getSeason(), $tab)) { 
                $season->setValidated(false);
                $em->persist($season);
                foreach ($season->getEpisodes() as $episode) { 
                    $episode->setSeason($season);
                    $episode->setValidated(false);
                    $em->persist($episode);
                    
                } 
                $em->flush();
            } else {
                //get the existing season entity and add new episodes
                $existingSeason = $em->getRepository('SiteBundle:Season')->findOneBy(array('series' => $season->getSeries(), 'season' => $season->getSeason()));
                // var_dump($existingSeason);
                // die;
                foreach ($season->getEpisodes() as $episode) { 
                    $episode->setSeason($existingSeason);
                    $episode->setValidated(false);
                    $em->persist($episode);
                
                }
                    $em->flush();
            } 

            return $this->redirectToRoute('series_show', array(
            'id' => $series->getId()));
        }

        return $this->render('season/new.html.twig', array(
            'season' => $season,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Season entity.
     *
     * @Route("season/{id}", name="season_show")
     * @Method("GET")
     */
    public function showAction(Season $season)
    {
        $deleteForm = $this->createDeleteForm($season);

        return $this->render('season/show.html.twig', array(
            'season' => $season,
            'delete_form' => $deleteForm->createView(),
        ));
    }

     /**
     * Validate an existing Season entity by moderator.
     *
     * @Route("/{id}/validate", requirements={
    *     "id": "\d+"}, name="season_validate")
     * @Method("GET")
     */
    public function validateAction(Season $season)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

        $em = $this->getDoctrine()->getManager();

         if (($season->getOldId())!==null) {
            $oldSeason = $em->getRepository('SiteBundle:Season')->find($season->getOldId());

            $oldSeason->setSeries($season->getSeries());
            $oldSeason->setSeason($season->getSeason());
            $oldSeason->setValidated(true);

            $em->persist($oldSeason);
            $em->remove($season); 
            $em->flush();

            return $this->redirectToRoute('moderator_index');
        }


        $season->setValidated(true);
        $em->persist($season);
        $em->flush();

        return $this->redirectToRoute('moderator_index');
    
    }

    /**
     * Displays a form to edit an existing Season entity.
     *
     * @Route("season/{id}/edit", requirements={
    *     "id": "\d+"}, name="season_episode_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Season $season)
    {
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup.');
        }
        
        $editForm = $this->createForm('SiteBundle\Form\SeasonType', $season);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
 
            $em = $this->getDoctrine()->getManager();

            $seasonCopy = new Season();
            $seasonCopy->setSeries($season->getSeries());
            $seasonCopy->setSeason($season->getSeason());
            $seasonCopy->setOldId($season->getId());
            $seasonCopy->setValidated(false);

            $em->detach($season);
            $em->persist($seasonCopy);

            foreach ($season->getEpisodes() as $episode) { 
                $episodeCopy= new Episode();
                $episodeCopy->setEpisode($episode->getEpisode());
                $episodeCopy->setTitle($episode->getTitle());
                $episodeCopy->setSynopsisEn($episode->getSynopsisEn());
                $episodeCopy->setSynopsisFr($episode->getSynopsisFr());
                $episodeCopy->setOldId($episode->getId());
                $episodeCopy->setValidated(false);
                $em->detach($episode);
                $em->persist($episodeCopy);
                } 

            $em->flush();

            return $this->redirectToRoute('moderator_index');
        }

        return $this->render('season/edit.html.twig', array(
            'season' => $season,
            'edit_form' => $editForm->createView(),
            
        ));
    }

    /**
     * Deletes a Season entity.
     *
     * @Route("season/{id}/delete", requirements={
     *   "id": "\d+"}, name="season_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, Season $season)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

            $em = $this->getDoctrine()->getManager();
            $em->remove($season);
            $em->flush();

        return $this->redirectToRoute('moderator_index');
    }

    /**
     * Creates a form to delete a Season entity.
     *
     * @param Season $season The Season entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Season $season)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('season_delete', array('id' => $season->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
