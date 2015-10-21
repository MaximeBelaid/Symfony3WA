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
use Troiswa\BackBundle\Entity\Commentaire;
use Troiswa\BackBundle\Entity\Product;
use Troiswa\BackBundle\Form\CommentaireType;
use Troiswa\BackBundle\Form\ProductType;
use Troiswa\BackBundle\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends  Controller
{
    /*private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('troiswa_back_product_delete', array('id' => $id)))
            ->getForm()
            ;
    }*/

    /**
     *
     * @ParamConverter("product", options={"repository_method" = "findProduitWithComments"})
     */
    public function showAction(/*$id*/Product $product, Request $request)
    {
        $commentaire = new Commentaire();
        $formulaireCommentaire = $this->createForm(new CommentaireType(), $commentaire)
            ->add("enregistrer","submit");
        $formulaireCommentaire->handleRequest($request);
        $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)
        //$product=$em->getRepository("TroiswaBackBundle:Product")
        //    ->findProduitWithComments($id);
        if($formulaireCommentaire->isValid()){
            $commentaire->setProduct($product);

            $em->persist($commentaire); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            //die(dump($commentaire));
            $this->get("session")->getFlashBag()->add("success","Bravo !");
            return $this->redirectToRoute("troiswa_back_product_show", ['id'=> $product->getId()]);
        }
        /*
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository("TroiswaBackBundle:Product")
            ->find($id);*/

/*
        if(!$product)
        {
            throw $this->createNotFoundException("Le produit n'existe pas");
        }
        //$deleteForm = $this->createDeleteForm($id);
        */
        /*$commentaires = $em->getRepository("TroiswaBackBundle:Commentaire")
            ->findBy(["product"=>$product->getId()],
                            ["dateCreation"=>"DESC"]);*/
        //die(dump($product));

        return $this->render('TroiswaBackBundle:Product:index.html.twig', array(
            'product'      => $product,
            "formCommentaire"=>$formulaireCommentaire->createView(),
            /*"commentaires"=>$commentaires*/
            /*'delete_form' => $deleteForm->createView(),*/
        ));}

    public function testAction()
    {
        return $this->render("TroiswaBackBundle:Product:test.html.twig");
    }

    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        /*$products=$em->getRepository("TroiswaBackBundle:Product")
                        ->findAll(); // http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
                        //->find(1);
                        //->findBy["title"=>"toto"],
                        //            ["date"=>"DESC"], 2,4);*/
        $products = $em->getRepository("TroiswaBackBundle:Product")
              ->findAllProductWithCategory();

        //die(dump($products));
        return $this->render("TroiswaBackBundle:Product:all.html.twig",["products"=>$products]);
    }

    public function createAction(Request $request)
    {
        $product = new Product();
        //$product->setTitle("Hello");
        /*
         $formulaireProduct = $this->createFormBuilder($product)
                                    ->add("title","text")
                                    //->add("toto","text") erreur car toto n'existe pas en base
                                    ->add("description", "textarea")
                                    ->add("price", "text")
                                    ->add("quantity", "number")
                                    ->add("sauvegarder", "submit")
                                    ->getForm();
        */
        $formulaireProduct = $this->createForm(new ProductType(), $product)
                                    ->add("enregistrer","submit");
        dump($product);
        $formulaireProduct->handleRequest($request);
        if($formulaireProduct->isValid()){
            $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)
            $em->persist($product); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            //die(dump($product));
            $this->get("session")->getFlashBag()->add("success","Bravo !");
            return $this->redirectToRoute("troiswa_back_product_create");
        }
        return $this->render("TroiswaBackBundle:Product:create.html.twig",["formProduct"=>$formulaireProduct->createView()]);
    }

    public function editAction($id, Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository("TroiswaBackBundle:Product")
            ->find($id);
        if(!$product)
        {
            throw $this->createNotFoundException("Le produit n'existe pas");
        }

        $formulaireProduct = $this->createFormBuilder($product)
            ->add("title","text")
            ->add("description", "textarea")
            ->add("price", "text")
            ->add("quantity", "number")
            ->add('categorie', "entity", [
                //"expanded"=>"true",
                "class"=>"TroiswaBackBundle:Categorie",
                "choice_label"=>"title",
                'query_builder' => function (CategorieRepository $er) {
                    return $er->builderCategoryOrderPosition();
                }])
            ->add('marque', "entity", [
                //"expanded"=>"true",
                "class"=>"TroiswaBackBundle:Marque",
                "choice_label"=>"titre"
            ])
            ->add("sauvegarder", "submit")
            ->getForm();
        $formulaireProduct->handleRequest($request);
        if($formulaireProduct->isValid()){
            $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)
            $em->persist($product); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            //die(dump($product));
            $this->get("session")->getFlashBag()->add("success","Bravo !");
            return $this->redirectToRoute("troiswa_back_product_edit", ['id'=>$id]);
        }
        return $this->render("TroiswaBackBundle:Product:edit.html.twig",["formProduct"=>$formulaireProduct->createView()]);
    }

    public function deleteAction(Request $request, $id)
    {
        /*
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
        */
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository('TroiswaBackBundle:Product')->find($id);

            if (!$product) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            // Code de la suppression
            $em->remove($product);
            $em->flush();
            if($request->isXmlHttpRequest()) { // est-ce que c'est de l'ajax
                return new JsonResponse([]);
            }
            // Fin code de la suppression

            $this->get("session")->getFlashBag()->add("success","Bravo !");
       // }

        return $this->redirect($this->generateUrl('troiswa_back_product')); // page qui liste tous les produits
    }
}