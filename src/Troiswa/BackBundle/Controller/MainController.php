<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 01/10/15
 * Time: 17:18
 */

namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    public function contactAction()
    {
        //return new Response("Hello");
        return $this->render("TroiswaBackBundle:Other:contact.html.twig");
    }

    public function aboutAction()
    {
        $products = [
            [
                "id" => 1,
                "title" => "Mon premier produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 10
            ],
            [
                "id" => 2,
                "title" => "Mon deuxième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 20
            ],
            [
                "id" => 3,
                "title" => "Mon troisième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 30
            ],
            [
                "id" => 4,
                "title" => "",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 410
            ],
        ];

        $prenom="Maxime";
        $nom="BELAID";

        return $this->render("TroiswaBackBundle:Other:about.html.twig",["produits" => $products,"prenom" => $prenom, "nom"=>$nom]);
    }

    public function etudiantAction($prenom, $nom)
    {
        return $this->render("TroiswaBackBundle:Other:etudiant.html.twig",["prenom" => $prenom, "nom"=>$nom]);
    }

    public function adminAction()
    {
        return $this->render("TroiswaBackBundle:Main:admin.html.twig");
    }
}