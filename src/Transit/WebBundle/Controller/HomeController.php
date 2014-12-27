<?php

namespace Transit\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TransitWebBundle:Home:index.html.twig');
    }
}
