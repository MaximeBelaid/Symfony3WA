<?php

namespace Troiswa\BackBundle\Util;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Troiswa\BackBundle\Entity\Product;

class Cart
{
    private $em;
    private $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function add(Product $product, $qty = 1)
    {
        // Soit j'ai un tableau vide soit un tableau plein d'id produit
        //die(dump($this->session->get('panier')));
        if($this->session->get('panier'))
        {
            $allProducts = $this->session->get('panier');
        }
        else
        {
            $allProducts = [];
        }

        // Traitement sur la quantité
        if (array_key_exists($product->getId(), $allProducts))
        {
            // $allProducts[$product->getId()]; représente la quantité du produit
            $allProducts[$product->getId()] = $allProducts[$product->getId()] + $qty;
            //$qty = $allProducts[$product->getId()] + $qty
        }
        else
        {
            $allProducts[$product->getId()] = $qty;
        }

        $this->session->set('panier', $allProducts);
    }

    public function getProducts()
    {
        $allProducts = [];

        if($this->session->get('panier'))
        {
            $allProducts = $this->em->getRepository('TroiswaBackBundle:Product')->findProductByIdProduct(array_keys($this->session->get('panier')));
        }

        return $allProducts;
    }

    public function getSessionPanier()
    {
        return $this->session->get('panier');
    }

}