<?php
/**
 * Created by PhpStorm.
 * User: wap21
 * Date: 02/10/15
 * Time: 17:07
 */

namespace Troiswa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}