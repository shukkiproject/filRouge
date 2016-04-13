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
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');
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
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');

        $userManager = $this->get('fos_user.user_manager');

        $userManipulator = new UserManipulator($userManager);
        
        $user=$userManipulator->addRole($username, 'ROLE_ADMIN');

        $users=$userManager->findUsers();
        return $this->render('admin/superadmin.html.twig', array('users' => $users,));
        
        
    }

    /**
     * @Route("/demote/{username}", name="superadmin_demote")
     * @Method("GET")
     */
    public function demoteAdminAction($username)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');

        $userManager = $this->get('fos_user.user_manager');

        $userManipulator = new UserManipulator($userManager);
        
        $user=$userManipulator->removeRole($username, 'ROLE_ADMIN');

        $users=$userManager->findUsers();
        return $this->render('admin/superadmin.html.twig', array('users' => $users,));
        
        
    }

    // /**
    //  * @Route("/admin/users/promoSuper/{username}", name="imie_blog_blog_promosuper")
    //  * @Method("GET")
    //  */
    // public function promoSuperAction($username)
    // {
    //     $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');
        
    //     $userManager = $this->get('fos_user.user_manager');

    //     $userManipulator = new UserManipulator($userManager);

    //     $userManipulator->promote($username);

    //     $users=$userManager->findUsers();
    //     return $this->render('default/superAdmin.html.twig', array('users' => $users,));
        
    // }


}
