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
 * Message controller.
 *
 * @Route("/mess")
 */
class MessController extends Controller
{
    /**
     * reply
     *
     * @Route("/{threadId}", name="message_reply")
     * })
     * @Method({"GET", "POST"})
     */
    public function replyAction(Request $request, $threadId){
        $provider = $this->container->get('fos_message.provider');
        $thread = $provider->getThread($threadId);
        $user = $this->getUser();

        $composer = $this->container->get('fos_message.composer');
        $message =  $request->request->get('reply');

        $mess = $composer->reply($thread)
            ->setSender($user)
            ->setBody($message)
            ->getMessage();

        $sender = $this->container->get('fos_message.sender');
        $sender->send($mess);

        return $this->redirectToRoute('user_profil');
    }
}
