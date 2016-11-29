<?php

namespace PlanMyLife\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReminderController extends Controller
{
    function indexAction() {
        return $this->render('PlanMyLifeFrontBundle::index.html.twig');
    }
}