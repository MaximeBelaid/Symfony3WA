<?php


namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\User;
use Troiswa\BackBundle\Form\UserType;


class UserController extends  Controller
{
    public function createAction(Request $request)
    {
        $user = new User();

        $formulaireUser = $this->createForm(new UserType(), $user);
        $formulaireUser->handleRequest($request);
        if($formulaireUser->isValid()){

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user); // Je récupère l'encoder de la class Troiswa\BackBundle\Entity\User
            $newPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());

            $user->setPassword($newPassword);


            $em= $this->getDoctrine()->getManager(); // Récupérer doctrine (se connecter à la base)
            $em->persist($user); // A partir de ce moment-là Doctrine le surveille
            $em->flush();
            //die(dump($product));
            $this->get("session")->getFlashBag()->add("success","Bravo !");
            return $this->redirectToRoute("troiswa_back_register");
        }
        return $this->render("TroiswaBackBundle:User:register.html.twig",["formUser"=>$formulaireUser->createView()]);
    }
}