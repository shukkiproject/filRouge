<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\User;


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
     * Finds and displays a User.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SiteBundle:User')->findOneById($id);
        // var_dump($user);die;
        return $this->render('users/show.html.twig', array(
            'user' => $user,
        ));
    }

    
}
