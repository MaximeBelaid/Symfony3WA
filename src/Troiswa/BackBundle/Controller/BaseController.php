<?php

namespace Troiswa\BackBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function breadcrumbs($items)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Dashboard", $this->generateUrl("troiswa_back_admin"));
        foreach($items as $label => $url)
        {
            if (!empty($url))
            {
                $breadcrumbs->addItem($label, $url);
            }
            else
            {
                $breadcrumbs->addItem($label);
            }
        }
    }
}