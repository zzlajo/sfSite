<?php

namespace Abroad\Bundle\CrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AbroadCrudBundle:Default:index.html.twig', array('name' => $name));
    }
    
    /**
     * @Route("cao/{name}", name = "cao"
     */
    public function helloAction($name)
    {
	return $this->render('AbroadCrudBundle:Default:base.html.twig', array(
	    'name' => $name
	));
    }
    
}
