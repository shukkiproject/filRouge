<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use SiteBundle\Entity\User;

/**
 * Main controller.
 *
 * @Route("/moderator")
 */
class ModeratorController extends Controller
{

    /**
     * @Route("/", name="moderator_index")
     */
    public function moderatorAction()
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

        $userManager = $this->get('fos_user.user_manager');

        $users=$userManager->findUsers();

        $em = $this->getDoctrine()->getManager();

        $series = $em->getRepository('SiteBundle:Series')->findByValidated(false);
        $persons = $em->getRepository('SiteBundle:Person')->findByValidated(false);
        $seasons = $em->getRepository('SiteBundle:Season')->findByValidated(false);
        $episodes = $em->getRepository('SiteBundle:Episode')->findByValidated(false);

        // var_dump($persons);
        // die;
        
        return $this->render('admin/moderator.html.twig', array('users' => $users, 'series' => $series, 'persons' => $persons, 'seasons' => $seasons, 'episodes' => $episodes ));
        
    }

    /**
     * @Route("/users/{id}/ban/{value}", name="moderator_ban")
     */
    public function banAction(User $user, $value)
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');
        $em = $this->getDoctrine()->getManager();
        if ($value==1) {
            $user->setEnabled(false);
        } else {
            $user->setEnabled(true);
        }
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('moderator_index');
        
    }
}
