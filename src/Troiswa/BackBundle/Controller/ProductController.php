<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 02/10/15
 * Time: 17:07
 */

namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Product;

class ProductController extends  Controller
{
    public function showAction($id)
    {
        return $this->render("TroiswaBackBundle:Product:index.html.twig",["idProd"=>$id]);
    }

    public function testAction()
    {
        return $this->render("TroiswaBackBundle:Product:test.html.twig");
    }

    public function createAction(Request $request)
    {
        $product = new Product();
        //$product->setTitle("Hello");
        $formulaireProduct = $this->createFormBuilder($product)
                                    ->add("title","text")
                                    //->add("toto","text") erreur car toto n'existe pas en base
                                    ->add("description", "textarea")
                                    ->add("price", "text")
                                    ->add("quantity", "number")
                                    ->add("sauvegarder", "submit")
                                    ->getForm();
        dump($product);
        $formulaireProduct->handleRequest($request);
        if($formulaireProduct->isValid()){
            $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)
            $em->persist($product); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            //die(dump($product));
            $this->get("session")->getFlashBag()->add("success_product_create","Le produit a bien été enregistré");
            return $this->redirectToRoute("troiswa_back_product_create");
        }
        return $this->render("TroiswaBackBundle:Product:create.html.twig",["formProduct"=>$formulaireProduct->createView()]);
    }
}