<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\MessageBundle\Controller\MessageController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Message controller.
 *
 * @Route("/mess")
 */
class MessagingController extends Controller
{
    /**
     * New message on profil
     *
     * @Route("/", name="message_newThread")
     * })
     * @Method({"GET","POST"})
     */
    public function newThreadAction(Request $request){
        $subject =  $request->request->get('subject');
        $body =  $request->request->get('body');
        $username =  $request->request->get('recipient');
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $recipient = $em->getRepository('SiteBundle:User')->findOneByUsername($username);
        $composer = $this->container->get('fos_message.composer');

        $message = $composer->newThread()
        ->setSender($user)
        ->addRecipient($recipient)
        ->setSubject($subject)
        ->setBody($body)
        ->getMessage();

        $sender = $this->container->get('fos_message.sender');
        $sender->send($message);

        return $this->redirectToRoute('user_profil');
    }

   
    /**
     * New message on User profil
     *
     * @Route("/new/{id}", name="message_new")
     * })
     * @Method({"GET","POST"})
     */
    public function newMessageAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();

        $subject =  $request->request->get('subject');
        $body =  $request->request->get('body');  
        $user = $this->getUser();
        $recipient = $em->getRepository('SiteBundle:User')->findOneById($id);

        $composer = $this->container->get('fos_message.composer');

        $message = $composer->newThread()
        ->setSender($user)
        ->addRecipient($recipient)
        ->setSubject($subject)
        ->setBody($body)
        ->getMessage();

        $sender = $this->container->get('fos_message.sender');
        $sender->send($message);

        return $this->redirectToRoute('user_profil');
    }

     /**
     * reply
     *
     * @Route("/reply/{threadId}", name="message_reply")
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

    /**
     * set message as  read
     *
     * @Route("/read/{threadId}", name="message_read")
     * })
     * @Method({"GET"})
     */
    public function readAction($threadId){
        $provider = $this->container->get('fos_message.provider');
        // get thread
        $thread = $provider->getThread($threadId);
        // get participants. we actually configure this for only one participants
        $participants = $thread->getParticipants();
        // get last message
        $message = $thread->getLastMessage();
        // set as read
        foreach($participants as $participant){
            $message->setIsreadbyParticipant($participant, true);
        }

        $response = new Response();

        return $response;

    }
}
