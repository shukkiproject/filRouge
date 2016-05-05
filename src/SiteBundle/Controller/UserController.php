<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\MessageBundle\Controller\MessageController;


/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all Series entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('SiteBundle:User')->findAll();
        
        return $this->render('users/index.html.twig', array(
            'users' => $users,
        ));
    }

     /**
     * Finds and displays my Profil
     *
     * @Route("/profil", name="user_profil")
     * @Method("GET")
     */
    public function profilAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        //$comments = $em->getRepository('SiteBundle:Comment')->findByUserid($user->getId());
        // $series = $em->getRepository('SiteBundle:Series')->findByUserid($user->getId());
        // if (!is_object($user) || !$user instanceof UserInterface) {
        //     throw new AccessDeniedException('This user does not have access to this section.');
        // }
        $provider = $this->container->get('fos_message.provider');
        $threads = $provider->getInboxThreads();
        return $this->render('users/showProfil.html.twig', array(
            'user' => $user,
            'threads' => $threads,
            //'comments' => $comments,
            // 'series' => $series,
        ));
    }

     /**
     * Finds and displays a User.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction($id)
    {   
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('SiteBundle:User')->findOneById($id);
        $provider = $this->container->get('fos_message.provider');
        $threads = $provider->getInboxThreads();
        $comments = $em->getRepository('SiteBundle:Comment')->findByUserid($id);
        $series = $em->getRepository('SiteBundle:Series')->findByUserid($id);
        return $this->render('users/showUser.html.twig', array(
            'user' => $user,
            'comments' => $comments,
            'series' => $series,
        ));
    }

}
