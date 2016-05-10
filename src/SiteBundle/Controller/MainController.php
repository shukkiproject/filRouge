<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Util\UserManipulator;
use SiteBundle\Entity\Series;
use SiteBundle\Repository\MainRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Main controller.
 *
 * @Route("/")
 */
class MainController extends Controller
{
  /**
     * @Route("/{_locale}", defaults={"_locale": "fr"}, requirements={"_locale": "en|fr"}, name="site_main_index")
     */
    public function indexAction(Request $request){
        $newLocale = $request->getLocale();   
        return $this->render('main/index.html.twig');
    } 

    /**
     * @Route("/{_locale}", defaults={"_locale": "fr"}, requirements={"_locale": "en|fr"}, name="site_main_language")
     */
    public function languageAction(Request $request){
        $newLocale = $request->getLocale();   
        $url = $this->getRequest()->headers->get("referer");
        var_dump($request);
        die;
        if (isset($url)) {
        $oldLocale = ($newLocale==='en')? 'fr' : 'en';   
        //replace the old by te new, even if it's the same language, it w'ont be affected.     
        $newUrl= str_replace($oldLocale, $newLocale, $url);

        return new RedirectResponse($newUrl); 
        }
        return $this->render('main/index.html.twig');
    } 


    /**
     * Search
     *
     * @Route("/{_locale}/search", defaults={"_locale": "fr"}, name="site_main_search", requirements={
     *     "_locale": "en|fr"
     * })
     * @Method("POST")
     */
    public function searchAction(Request $request){
      
        $search =  $request->request->get('search');
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('SiteBundle:User')->searchMethod($search);
        $series = $em->getRepository('SiteBundle:Series')->searchMethod($search);
        $episodes = $em->getRepository('SiteBundle:Episode')->searchMethod($search);
        return $this->render(':search:search.html.twig',[
            'search' => $search,
            'series'=>$series,
            'episodes'=>$episodes,
            'users'=>$users
        ]);
    }

    /**
     * search ajax
     *
     * @Route("/{_locale}/searchAutocomplete", defaults={"_locale": "fr"}, name="site_main_searchAutocomplete", requirements={
     *     "_locale": "en|fr"
     * })
     * @Method("GET")
     */
    public function searchAutocompleteAction(Request $request)
    {

        $request = $this -> get('request');
        if($request->isXmlHttpRequest())
        {
            $term = $request->query->get('motcle');
            $em = $this->getDoctrine()->getManager();
            $array= $em
                ->getRepository('SiteBundle:Series')
                ->listeSeries($term);
            $response = new Response(json_encode($array));
            $response -> headers -> set('Content-Type', 'application/json');
            return $response;
       }
    }


}
