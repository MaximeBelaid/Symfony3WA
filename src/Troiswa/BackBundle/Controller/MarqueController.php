<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Troiswa\BackBundle\Entity\Marque;
use Troiswa\BackBundle\Form\MarqueType;
use Troiswa\BackBundle\Controller\BaseController;

/**
 * Marque controller.
 *
 */
class MarqueController extends BaseController
{

    /**
     * Lists all Marque entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('TroiswaBackBundle:Marque')->findAll();
        $query = $em->createQuery("SELECT mar FROM TroiswaBackBundle:Marque mar");

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            // $request->query = $_GET[]     $_GET['page']
            $request->query->getInt('page', 1)/*page number*/, // marque/adidas/show?page=2
            5/*limit per page*/
        );

        return $this->render('TroiswaBackBundle:Marque:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Marque entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Marque();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('marque_show', array('id' => $entity->getSlug
            ())));
        }

        return $this->render('TroiswaBackBundle:Marque:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Marque entity.
     *
     * @param Marque $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Marque $entity)
    {
        $form = $this->createForm(new MarqueType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('marque_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Marque entity.
     *
     */
    public function newAction()
    {
        $entity = new Marque();
        $form   = $this->createCreateForm($entity);

        return $this->render('TroiswaBackBundle:Marque:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * path: /marque/{id}/show
     * entity = nom de la variable
     * id = variable du routing
     * slug = nom de la colonne dans la base de données
     * @ParamConverter("entity",  options={"mapping":  {"id" = "slug"}})
     */
    public function showAction(/*$id*/Marque $entity)
    {
        /*
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Dashboard", $this->generateUrl("troiswa_back_admin"));
        $breadcrumbs->addItem("Marque", $this->generateUrl("marque"));
        $breadcrumbs->addItem($entity->getTitre());
        */
        $this->breadcrumbs(
            [
                'Marque' => $this->generateUrl("marque"),
                $entity->getTitre() => ''
            ]
        );

        /*
                $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example without parameter
                $breadcrumbs->addItem("Home", "http://www.google.fr");
                $breadcrumbs->addItem("Home", $this->get("router")->generate("troiswa_back_page_bo"));

        // Simple example with parameter
                $breadcrumbs->addItem("Home", $this->get("router")->generate("troiswa_back_page_bo", ['id' => 1]));
                $breadcrumbs->addItem("Home", $this->generateUrl("troiswa_back_page_bo", ['id' => 1]));

        // Simple example with parameter
                $breadcrumbs->addRouteItem("Home", "troiswa_back_page_bo");
                $breadcrumbs->addRouteItem("Hello", "troiswa_back_page_bo", [
                    'id' => 1,
                ]);

        // Simple text
                $breadcrumbs->addItem("Some text without link");
        */

        //die(dump($entity));
        $em = $this->getDoctrine()->getManager();

        //$entity = $em->getRepository('TroiswaBackBundle:Marque')->findOneBy(["slug"=>$id]);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Marque entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());

        return $this->render('TroiswaBackBundle:Marque:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Marque entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:Marque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Marque entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TroiswaBackBundle:Marque:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Marque entity.
    *
    * @param Marque $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Marque $entity)
    {
        $form = $this->createForm(new MarqueType(), $entity, array(
            'action' => $this->generateUrl('marque_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Marque entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:Marque')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Marque entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('marque_edit', array('id' => $id)));
        }

        return $this->render('TroiswaBackBundle:Marque:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Marque entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TroiswaBackBundle:Marque')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Marque entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('marque'));
    }

    /**
     * Creates a form to delete a Marque entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('marque_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
