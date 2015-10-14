<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 05/10/15
 * Time: 10:29
 */

namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Troiswa\BackBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Form\CategorieType;

class CategorieController extends Controller
{
    public function indexAction()
    {
        /*$categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];

        return $this->render("TroiswaBackBundle:Categorie:index.html.twig", ["categories"=>$categories]);*/
        $em=$this->getDoctrine()->getManager();
        $categories=$em->getRepository("TroiswaBackBundle:Categorie")
            ->findAll(); // http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html

        return $this->render("TroiswaBackBundle:Categorie:all.html.twig",["categories"=>$categories]);

    }

    public function showAction(/*$id*/ Categorie $categorie)
    {
        /*$categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];

        if(array_key_exists($id, $categories))
        {
            $uneCategorie = $categories[$id];
        }else{
            throw $this->createNotFoundException("La catégorie n'existe pas");
        }

        return $this->render("TroiswaBackBundle:Categorie:zoom.html.twig", ["categorie"=>$uneCategorie]);
        */

        /* Utilisation du paramConverter au niveau de la méthode
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("TroiswaBackBundle:Categorie")
            ->find($id);

        if(!$categorie)
        {
            throw $this->createNotFoundException("La catégorie n'existe pas");
        }
        */
        return $this->render('TroiswaBackBundle:Categorie:index.html.twig', array(
            'categorie'      => $categorie,
        ));
    }

    public function createAction(Request $request)
    {
        $categorie = new Categorie();

        $formulaireCategorie = $this->createForm(new CategorieType(), $categorie)
            ->add("enregistrer","submit");
        $formulaireCategorie->handleRequest($request);
        if($formulaireCategorie->isValid()){
            //die(dump($categorie));

            $image=$categorie->getImage();
            $image->upload();

            $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)

            //Je supprime les 2 lignes de dessous car cascade persist dans l'entity Category
            //$em->persist($image);
            //$em->flush();

            $em->persist($categorie); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            $this->get("session")->getFlashBag()->add("success","Bravo !");
            return $this->redirectToRoute("troiswa_back_categorie_create");
        }
        return $this->render("TroiswaBackBundle:Categorie:create.html.twig",["formCategorie"=>$formulaireCategorie->createView()]);
    }

    public function editAction($id, Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("TroiswaBackBundle:Categorie")
            ->find($id);
        if(!$categorie)
        {
            throw $this->createNotFoundException("La catégorie n'existe pas");
        }
        $formulaireCategorie = $this->createForm(new CategorieType(), $categorie)
            ->add("enregistrer","submit");
        $formulaireCategorie->handleRequest($request);
        if($formulaireCategorie->isValid()){
            $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)
            $em->persist($categorie); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            $this->get("session")->getFlashBag()->add("success","Bravo !");
            return $this->redirectToRoute("troiswa_back_categorie_edit", ['id'=>$id]);
        }
        return $this->render("TroiswaBackBundle:Categorie:edit.html.twig",["formCategorie"=>$formulaireCategorie->createView()]);
    }

    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('TroiswaBackBundle:Categorie')->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        // Code de la suppression
        $em->remove($categorie);
        $em->flush();
        // Fin code de la suppression

        $this->get("session")->getFlashBag()->add("success","Bravo !");
        // }

        return $this->redirect($this->generateUrl('troiswa_back_categorie')); // page qui liste tous les produits
    }

    public function renderAllCategorieAction()
    {
        $em=$this->getDoctrine()->getManager();
        $categories=$em->getRepository("TroiswaBackBundle:Categorie")
            ->findAll();
            /*->findBy(["title"=>"CAT-2"],
                ["dateCreated"=>"DESC"],2,0);*/

        return $this->render("TroiswaBackBundle:Categorie/test:renderCategorie.html.twig",["categories"=>$categories]);

    }
}