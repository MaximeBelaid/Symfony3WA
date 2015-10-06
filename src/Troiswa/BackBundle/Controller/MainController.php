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

class MainController extends Controller
{
    public function contactAction(Request $request)
    {
        //return new Response("Hello");
        $formulaireContact = $this->createFormBuilder()
                                    ->add("firstname","text")
                                    ->add("lastname","text")
                                    ->add("email","email")
                                    ->add("content","textarea")
                                    ->add("send","submit")
                                    ->getForm(); // récupère le formulaire
        if("POST"===$request->getMethod())
        {
            //die(dump($request->request->all()));
            $formulaireContact->bind($request);
            if($formulaireContact->isValid())
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
        return $this->render("TroiswaBackBundle:Main:admin.html.twig");
    }

    public function feedbackAction(Request $request)
    {
        $formulaireFeedback = $this->createFormBuilder()
            ->add("page","url")
            ->add("bug",'choice', array(
                'choices' => array(
                    'integration'   => 'Integration',
                    'developemment' => 'Dévelopemment',
                    'autre'   => 'Autre',
                ),
                'multiple' => false,
                'expanded' => false))
            ->add("firstname","text")
            ->add("email","email")
            ->add("date",'date', array(
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