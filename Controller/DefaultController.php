<?php

namespace PlanMyLife\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PlanMyLifeFrontBundle:Default:index.html.twig');
    }
}
