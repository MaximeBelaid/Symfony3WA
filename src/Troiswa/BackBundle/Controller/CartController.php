<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Product;


class CartController extends Controller
{

    public function addAction(Product $product, Request $request)
    {

     /*
        $session = $request->getSession();

        //$allProducts = [];
        //die(dump($session->get('panier')));


        // Soit j'ai un tableau vide soit un tableau plein d'id produit
        if($session->get('panier'))
        {
            $allProducts = $session->get('panier');
            //dump($allProducts);
        }
        else
        {
            $allProducts = [];
        }

        // Traitement sur la quantité
        if (array_key_exists($product->getId(), $allProducts))
        {
            //die(dump($allProducts[$product->getId()], $allProducts));
            // $allProducts[$product->getId()]; représente la quantité du produit
            $allProducts[$product->getId()] = $allProducts[$product->getId()] + 1;

        }
        else
        {
            $allProducts[$product->getId()] = 1;
        }

        $session->set('panier', $allProducts);
        //die(dump($session->get('panier')));
        //die('traitement du produit dans le panier');
 */
        $panier = $this->get('troiswa_back.cart');
        //die(dump($panier));
        $qty = $request->request->getInt("qty",1); //$_POST
        // ou
        //$qty = $request->request->getInt("qty",1); //$_GET
        $panier->add($product,$qty );

        return $this->redirectToRoute('troiswa_back_panier');


    }


    public function panierAction(Request $request)
    {
        /*
        $session = $request->getSession();
        $allProducts = [];

        if($session->get('panier'))
        {
            $em = $this->getDoctrine()->getManager();
            $allProducts = $em->getRepository('TroiswaBackBundle:Product')->findProductByIdProduct(array_keys($session->get('panier')));

        }
        //die(dump($session->get('panier'),$allProducts));
        return $this->render("TroiswaBackBundle:Cart:panier.html.twig", ['allProducts' => $allProducts, 'quantity' => $session->get('panier')]);
        */


        $panier = $this->get('troiswa_back.cart');

        return $this->render("TroiswaBackBundle:Cart:panier.html.twig",
            [
                'allProducts' => $panier->getProducts(),
                'qtyProducts' => $panier->getSessionPanier()
            ]);
    }

    public function deleteAction(Product $product, Request $request)
    {
        $session = $request->getSession();
        $cart = $session->get('panier');

        if ($cart && array_key_exists($product->getId(), $cart))
        {
            unset($cart[$product->getId()]);
            $session->set('panier', $cart);
        }

        return $this->redirectToRoute('troiswa_back_panier');
    }

}