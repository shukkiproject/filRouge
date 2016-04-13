<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Util\UserManipulator;

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

        $em = $this->getDoctrine()->getManager();

        $series = $em->getRepository('SiteBundle:Series')->findByValidated(false);
        // var_dump($series);
        // die;

        return $this->render('default/admin.html.twig', array('users' => $users, 'series' => $series ));
        
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
