<?php

namespace Apu\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ApuUserBundle:Default:index.html.twig');
    }
}
