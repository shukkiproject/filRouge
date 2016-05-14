<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\UserBundle\Util\UserManipulator;

/**
 * Main controller.
 *
 * @Route("/superadmin")
 */
class SuperAdminController extends Controller
{

     /**
     * @Route("/", name="superadmin_index")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unauthorized to access this page!');
        $userManager = $this->get('fos_user.user_manager');
        $users=$userManager->findUsers();

        return $this->render('admin/superadmin.html.twig', array('users' => $users,));
    
    }

    /**
     * @Route("/promote/{username}", name="superadmin_promote")
     * @Method("GET")
     */
    public function promoAdminAction($username)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unauthorized to access this page!');

        $userManager = $this->get('fos_user.user_manager');

        $userManipulator = new UserManipulator($userManager);
        
        $user=$userManipulator->addRole($username, 'ROLE_MODERATOR');

        $users=$userManager->findUsers();
        return $this->render('admin/superadmin.html.twig', array('users' => $users,));
        
        
    }

    /**
     * @Route("/demote/{username}", name="superadmin_demote")
     * @Method("GET")
     */
    public function demoteAdminAction($username)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unauthorized to access this page!');

        $userManager = $this->get('fos_user.user_manager');

        $userManipulator = new UserManipulator($userManager);
        
        $user=$userManipulator->removeRole($username, 'ROLE_MODERATOR');

        $users=$userManager->findUsers();
        return $this->render('admin/superadmin.html.twig', array('users' => $users,));
        
        
    }

    // /**
    //  * @Route("/admin/users/promoSuper/{username}", name="site_promosuper")
    //  * @Method("GET")
    //  */
    // public function promoSuperAction($username)
    // {
    //     $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unauthorized to access this page!');
        
    //     $userManager = $this->get('fos_user.user_manager');

    //     $userManipulator = new UserManipulator($userManager);

    //     $userManipulator->promote($username);

    //     $users=$userManager->findUsers();
    //     return $this->render('default/superAdmin.html.twig', array('users' => $users,));
        
    // }

    /**
     * @Route("/moderator", name="moderator_index")
     */
    public function moderatorAction()
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

        $userManager = $this->get('fos_user.user_manager');

        $users=$userManager->findUsers();

        $em = $this->getDoctrine()->getManager();

        $series = $em->getRepository('SiteBundle:Series')->findByValidated(false);
        $persons = $em->getRepository('SiteBundle:Person')->findByValidated(false);

        // var_dump($persons);
        // die;
        
        return $this->render('admin/moderator.html.twig', array('users' => $users, 'series' => $series, 'persons' => $persons ));
        
    }


}
