<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 01/10/15
 * Time: 17:18
 */

namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class MainController extends Controller
{
    public function contactAction(Request $request)
    {
        //return new Response("Hello");
        $formulaireContact = $this->createFormBuilder()
                                    ->add("firstname","text",
                                        [
                                            "constraints"=>[new Assert\NotBlank(["message"=>"N'oublie pas d'entrer ton prénom"]),  new Assert\Length(array(
                                                'min'        => 2,
                                                'minMessage' => 'Ton prénom doit avoir au moins {{ limit }} caractères'
                                            )) ],

                                            "required"=>false  //false = désactive la validation html5 pour un champ
                                        ])
                                    ->add("lastname","text",
                                        [
                                            "constraints"=>[new Assert\NotBlank(["message"=>"N'oublie pas d'entrer ton nom"])],
                                            "required"=>false  //false = désactive la validation html5 pour un champ
                                        ])
                                    ->add("email","email",
                                        [
                                            "constraints"=>[new Assert\NotBlank(["message"=>"N'oublie pas d'entrer ton email"]), new Assert\Email(array(
                                                'message' => 'Ton email bizarre "{{ value }}" n\'est pas valide.',
                                                'checkMX' => true,
                                            ))],
                                            "required"=>false  //false = désactive la validation html5 pour un champ
                                        ])
                                    ->add("content","textarea",
                                        [
                                            "constraints"=>[new Assert\NotBlank(["message"=>"N'oublie pas d'entrer ta description"]),  new Assert\Length(array(
                                                'min'        => 10,
                                                'max'        => 100,
                                                'minMessage' => 'Your content must be at least {{ limit }} characters long',
                                                'maxMessage' => 'Your content cannot be longer than {{ limit }} characters',
                                            ))],
                                            "required"=>false  //false = désactive la validation html5 pour un champ
                                        ])
                                    ->add("send","submit")
                                    ->getForm(); // récupère le formulaire
        /*
        if("POST"===$request->getMethod())
        {
            //die(dump($request->request->all()));
            $formulaireContact->bind($request);
            if($formulaireContact->isValid()) //test du Token
            {
                $data=$formulaireContact->getData();
                //die(dump($data));
                $message= \Swift_Message::newInstance()
                    ->setSubject('Hello Maxime BELAID !!!!!!!')
                    ->setFrom('maxime.belaid@gmail.com')
                    ->setTo('maxime.belaid@gmail.com')
                    ->setBody(
                        $this->renderView(
                        "TroiswaBackBundle:Emails:contact.html.twig",
                            array("data"=>$data)
                        ),
                        'text/html'
                    );
                $hasSend = $this->get("mailer")->send($message);
                if($hasSend)
                    $this->get("session")->getFlashBag()->add("success_contact","Le mail a bien été envoyé");
                return $this->redirectToRoute("troiswa_back_page_contact");
            }
        }*/ /* Equivalent ci-dessous */

        $formulaireContact->handleRequest($request);
        if($formulaireContact->isValid()) //test du Token
        {
            $data=$formulaireContact->getData();
            $message= \Swift_Message::newInstance()
                ->setSubject('Hello Maxime BELAID !!!!!!!')
                ->setFrom('maxime.belaid@gmail.com')
                ->setTo('maxime.belaid@gmail.com')
                ->setBody(
                    $this->renderView(
                        "TroiswaBackBundle:Emails:contact.html.twig",
                        array("data"=>$data)
                    ),
                    'text/html'
                );
            $hasSend = $this->get("mailer")->send($message);
            if($hasSend)
                $this->get("session")->getFlashBag()->add("success_contact","Le mail a bien été envoyé");
            return $this->redirectToRoute("troiswa_back_page_contact");
        }
        return $this->render("TroiswaBackBundle:Other:contact.html.twig",["formContact"=>$formulaireContact->createView()]);
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
        $em = $this->getDoctrine()->getManager();
        $productAll= $em->getRepository("TroiswaBackBundle:Product")
                        //->findAllPerso();
                        ->findPerso(46);
        $productQuantity = $em->getRepository("TroiswaBackBundle:Product")
                        ->findProductQuantity();

        $nbProductNoQuantity = $em->getRepository("TroiswaBackBundle:Product")
            ->findProductNoQuantity();

        $nbCategory = $em->getRepository("TroiswaBackBundle:Product")
            ->findBestProductPriceAndQuantity();

        $nbCategoryActif = $em->getRepository("TroiswaBackBundle:Categorie")
            ->findNbCategoryActif();

        $nbCategoryActifInactif = $em->getRepository("TroiswaBackBundle:Categorie")
            ->findNbCategoryActifInactif();

        $totalPriceProduct = $em->getRepository("TroiswaBackBundle:Product")
            ->findTotalPriceProduct();

        $totalQuantityProduct = $em->getRepository("TroiswaBackBundle:Product")
            ->findTotalQuantityProduct();

        $bestAndLessProductPrice = $em->getRepository("TroiswaBackBundle:Product")
            ->findBestAndLessProductPrice();

        $bestProductPriceAndQuantity = $em->getRepository("TroiswaBackBundle:Product")
            ->findBestProductPriceAndQuantity();

        $categoryPosition = $em->getRepository("TroiswaBackBundle:Categorie")
            ->findCategoryPosition();

        //die(dump($productAll));
        return $this->render("TroiswaBackBundle:Main:admin.html.twig",["prenom"=>"Maxime BELAID",
            "productAll"=>$productAll,
            "productQuantity"=>$productQuantity,
            "nbProductNoQuantity"=>$nbProductNoQuantity,
            "nbCategory"=>$nbCategory,
            "nbCategoryActif"=>$nbCategoryActif,
            "nbCategoryActifInactif"=>$nbCategoryActifInactif,
            "totalPriceProduct"=>$totalPriceProduct,
            "totalQuantityProduct"=>$totalQuantityProduct,
            "bestAndLessProductPrice"=>$bestAndLessProductPrice,
            "bestProductPriceAndQuantity"=>$bestProductPriceAndQuantity,
            "categoryPosition"=>$categoryPosition]);
    }

    public function feedbackAction(Request $request)
    {
        $formulaireFeedback = $this->createFormBuilder()
            ->add("page","url")
            ->add("bug",'choice', array(
                'choices' => array(
                    'integration'   => 'Intégration',
                    'developpemment' => 'Développemment',
                    'autre'   => 'Autre',
                ),
                'constraints' => new Assert\Choice(
                    array(
                        'choices' =>
                            array(
                                'integration',
                                'developpemment',
                                'autre'
                            ),
                        'message' => "Avec ta d'enculé!",
                    )
                ),
                'multiple' => false,
                'expanded' => false))
            ->add("firstname","text")
            ->add("email","email")
            ->add("date",'date', array(
                "constraints" => new Assert\Date(),
                'input'  => 'datetime',
                'widget' => 'choice',
                'years' => array(date("Y")-1,date("Y"))
                ))
            ->add("send","submit")
            ->getForm();
        if("POST"===$request->getMethod())
        {
            $formulaireFeedback->bind($request);
            if($formulaireFeedback->isValid())
            {
                $data=$formulaireFeedback->getData();
                $message= \Swift_Message::newInstance()
                    ->setSubject('Voici nos feedbacks')
                    ->setFrom('maxime.belaid@gmail.com')
                    ->setTo('maxime.belaid@gmail.com')
                    ->setBody(
                        $this->renderView(
                            "TroiswaBackBundle:Emails:feedback.html.twig",
                            array("data"=>$data)
                        ),
                        'text/html'
                    );
                $this->get("mailer")->send($message);
                $this->get("session")->getFlashBag()->add("success_feedback","Le mail a bien été envoyé");
                return $this->redirectToRoute("troiswa_back_page_feedback");
            }
        }
        return $this->render("TroiswaBackBundle:Other:feedback.html.twig",["formFeedback"=>$formulaireFeedback->createView()]);
    }

}