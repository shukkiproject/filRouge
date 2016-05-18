<?php

namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SiteBundle\Entity\Person;
use SiteBundle\Form\PersonType;
use SiteBundle\Entity\Series;

/**
 * Person controller.
 *
 * @Route("/")
 */
class PersonController extends Controller
{
    /**
     * Lists all Person entities.
     *
     * @Route("person/", name="person_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('SiteBundle:Person')->findAll();

        return $this->render('person/index.html.twig', array(
            'people' => $people,
        ));
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("series/{id}/person/new", name="person_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Series $series)
    {
        $person = new Person();
        $form = $this->createForm('SiteBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $person->setValidated(false);
            //?
            $series->addPerson($person);
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('series_show', array('id' => $series->getId()));
        }

        return $this->render('person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("person/{id}", name="person_show")
     * @Method("GET")
     */
    public function showAction(Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);

        return $this->render('person/show.html.twig', array(
            'person' => $person,
            'delete_form' => $deleteForm->createView(),
        ));
    }

     /**
         * Validate an existing Person entity by admin.
         *
         * @Route("person/{id}/validate", name="person_validate")
         * @Method("GET")
         */
        public function validateAction(Person $person)
        {
            $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');

            $em = $this->getDoctrine()->getManager();

            if (($person->getOldId())!==null) {
                $oldPerson = $em->getRepository('SiteBundle:Person')->find($person->getOldId());
                if (isset($oldPerson)) {

                    $oldPerson->setLastname($person->getLastname());
                    $oldPerson->setFirstname($person->getFirstname());
                    $oldPerson->setCharacter($person->getCharacter());
                    $oldPerson->setValidated(true);
                    $em->persist($oldPerson);
                    $em->remove($person);
                    $em->flush();
                    return $this->redirectToRoute('moderator_index');
                }
       
            }
                $person->setValidated(true);
                $em->persist($person);
                $em->flush();

            return $this->redirectToRoute('moderator_index');
        
        }
    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("person/{id}/edit", name="person_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Please login or signup.');
        }
        $editForm = $this->createForm('SiteBundle\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $personCopy= new Person();
            $personCopy->setLastname($person->getLastname());
            $personCopy->setFirstname($person->getFirstname());
            $personCopy->setCharacter($person->getCharacter());
            $personCopy->setOldId($person->getId());
            $personCopy->setValidated(false);
            $em->detach($person);
            $em->persist($personCopy);            
            $em->flush();

            return $this->redirectToRoute('moderator_index');
        }

        return $this->render('person/edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
        ));
    }



    /**
     * Deletes a Person entity.
     *
     * @Route("person/{id}/delete", name="person_delete")
     * @Method("GET")
     */
    public function deleteAction(Person $person)
    {
            $this->denyAccessUnlessGranted('ROLE_MODERATOR', null, 'Unauthorized to access this page!');
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush();

        return $this->redirectToRoute('moderator_index');
    }

    /**
     * Creates a form to delete a Person entity.
     *
     * @param Person $person The Person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('person_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
