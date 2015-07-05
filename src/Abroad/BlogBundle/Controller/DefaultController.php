<?php

namespace Abroad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AbroadBlogBundle:Default:index.html.twig', array('name' => $name));
    }
}
