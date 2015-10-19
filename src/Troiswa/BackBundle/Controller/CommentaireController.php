<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 02/10/15
 * Time: 17:07
 */

namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CommentaireController extends  Controller
{

    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        $commentaires=$em->getRepository("TroiswaBackBundle:Commentaire")
            ->findAll();

        return $this->render("TroiswaBackBundle:Commentaire:all.html.twig",["commentaires"=>$commentaires]);
    }

    public function activeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('TroiswaBackBundle:Commentaire')->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $commentaire->setActive(!$commentaire->getActive());
        $em->flush();

        if($request->isXmlHttpRequest()) { // est-ce que c'est de l'ajax
            return new JsonResponse([]);
        }

        $this->get("session")->getFlashBag()->add("success","Bravo !");

        return $this->redirect($this->generateUrl('troiswa_back_commentaire')); // page qui liste tous les produits
    }
}