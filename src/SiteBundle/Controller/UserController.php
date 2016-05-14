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
     * Lists all Users entities.
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
    public function showProfilAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $provider = $this->container->get('fos_message.provider');
        //get inboxthreads of user
        $inboxThreads = $provider->getInboxThreads();
        //get sentboxthreads of user
        $sentThreads = $provider->getSentThreads();
        // make only one tab
        $threads = array_merge($inboxThreads, $sentThreads);
        // Removes duplicate values
        $threads = array_unique($threads,SORT_REGULAR);

        $friendsId = $user->getMyFriends();
        $friends= $em->getRepository('SiteBundle:User')->findById($friendsId);

        return $this->render('users/showProfil.html.twig', array(
            'user' => $user,
            'threads' => $threads,
            'friends' => $friends,
        ));
    }

     /**
     * Finds and displays a User.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showUserAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SiteBundle:User')->findOneById($id);

        if ($user === $this->getUser()){
            return $this->redirectToRoute('user_profil');
        }

        $friendsId = $user->getMyFriends();
        $friends= $em->getRepository('SiteBundle:User')->findById($friendsId);

        return $this->render('users/showUser.html.twig', array(
            'user' => $user,
            'friends' => $friends,
        ));
    }

     /**
     * Became Friends
     *
     * @Route("/friend/{id}", name="user_friend")
     * @Method("GET")
     */
    public function friendAction($id)
    {   

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->addMyFriend($id);

        $friend = $em->getRepository('SiteBundle:User')->findOneById($id);
        $userId = $user->getId();

        $friend->addMyFriend($userId);

        $em->persist($user);
        $em->persist($friend);
        $em->flush();

        return $this->redirectToRoute('user_profil');
    }

    /**
     * Remove Friends
     *
     * @Route("/removefriend/{id}", name="user_removefriend")
     * @Method("GET")
     */
    public function removeFriendAction($id)
    {   
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $user->removeMyFriend($id);

        $friend = $em->getRepository('SiteBundle:User')->findOneById($id);
        $userId = $user->getId();
        $friend->removeMyFriend($userId);

        $em->persist($user);
        $em->persist($friend);
        $em->flush();

        return $this->redirectToRoute('user_profil');
    }
}
