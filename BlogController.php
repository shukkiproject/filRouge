<?php

namespace Imie\BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Util\UserManipulator;


/**
 * Blog controller.
 *
 * @Route("/")
 */
class BlogController extends Controller
{
    
   /**
     * @Route("/{_locale}", defaults={"_locale": "en"}, requirements={
     *     "_locale": "en|fr"
     * })
     */
    public function indexAction(Request $request){
        $locale = $request->getLocale();    
        $response = $this->forward('ImieBlogBundle:Article:index', array('page'=> '1'));
        return $response; 
    } 

     /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this admin page!');
        $userManager = $this->get('fos_user.user_manager');
        
        $users=$userManager->findUsers();

        return $this->render('ImieBlogBundle:Blog:admin.html.twig', array('users' => $users,));
        
    
    }

     /**
     * @Route("/admin/users")
     */
    public function adminUsersAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');
        $userManager = $this->get('fos_user.user_manager');
        $users=$userManager->findUsers();

        return $this->render('ImieBlogBundle:Blog:superAdmin.html.twig', array('users' => $users,));
        
        
        // return new Response('<html><body>Admin Users page!</body></html>');
    }

     /**
     * @Route("/admin/users/promoAdmin/{username}", name="imie_blog_blog_promoadmin")
     * @Method("GET")
     */
    public function promoAdminAction($username)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');

        $userManager = $this->get('fos_user.user_manager');

        $userManipulator = new UserManipulator($userManager);
        
        $user=$userManipulator->addRole($username, 'ROLE_ADMIN');

        $users=$userManager->findUsers();
        return $this->render('ImieBlogBundle:Blog:superAdmin.html.twig', array('users' => $users,));
        
        
    }

    /**
     * @Route("/admin/users/promoSuper/{username}", name="imie_blog_blog_promosuper")
     * @Method("GET")
     */
    public function promoSuperAction($username)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');
        
        $userManager = $this->get('fos_user.user_manager');

        $userManipulator = new UserManipulator($userManager);

        $userManipulator->promote($username);

        $users=$userManager->findUsers();
        return $this->render('ImieBlogBundle:Blog:superAdmin.html.twig', array('users' => $users,));
        
    }


}
