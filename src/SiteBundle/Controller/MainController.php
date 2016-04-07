<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Main controller.
 *
 * @Route("/")
 */
class MainController extends Controller
{
  /**
     * @Route("/{_locale}", defaults={"_locale": "fr"}, requirements={
     *     "_locale": "en|fr"
     * })
     */
    public function indexAction(Request $request){
        $locale = $request->getLocale();    
        // $response = $this->forward('ImieBlogBundle:Article:index', array('page'=> '1'));
        // return $response; 
         return $this->render('default/index.html.twig');
    } 

     /**
     * @Route("/{_locale}/admin")
     */
    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this admin page!');

        $userManager = $this->get('fos_user.user_manager');
        $users=$userManager->findUsers();

        return $this->render('default/admin.html.twig', array('users' => $users,));
        
        // return new Response('<html><body>Admin page!</body></html>');
    }

     /**
     * @Route("/{_locale}/admin/users")
     */
    public function adminUsersAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Unable to access this super admin page!');
        $userManager = $this->get('fos_user.user_manager');
        $users=$userManager->findUsers();

        return $this->render('default/admin.html.twig', array('users' => $users,));
        
        
        // return new Response('<html><body>Admin Users page!</body></html>');
    }

}
