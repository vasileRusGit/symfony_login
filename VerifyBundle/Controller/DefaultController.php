<?php

namespace Verify\VerifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VerifyVerifyBundle:Default:index.html.twig');
    }
}
