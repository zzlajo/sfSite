<?php

namespace Abroad\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Abroad\UserBundle\Form\Type\FunGroupType;
use Abroad\UserBundle\Entity\FunGroup;

class FunGroupController extends Controller {
    
    public function newAction() {
	
	$funGroup = new FunGroup();
	$form = $this->createForm(new FunGroupType, $funGroup);
	$request = $this->getRequest();
	
	$form->bind($request);
	
	if ($form->isValid()) {

	    $funGroup->addUser($this->getUser());
	    $em = $this->getDoctrine()->getEntityManager();
            
//$em = $this->get('doctrine')->getManager();

//		    $funGroup->setUsers($this->getUser());
//	    var_dump($this->getUser());
//	    var_dump($ff); die;
//	    $group->addUser($this->getUser());
	    $em->persist($funGroup);
	    $em->flush();

	    
	    
//	    return $this->redirect($this->generateUrl('AbroadUserBundle:FunGroup:show.html.twig'));
	    return $this->redirectToRoute('abroad_user_fungroup_list');
	    
	    
	}
	
	return $this->render('AbroadUserBundle:FunGroup:form.html.twig', array(
	    'funGroup' => $funGroup,
	    'form' => $form->createView()
	));
	
    }
    
    public function listAction() {
	
	$groups = $this->getDoctrine()->getRepository('AbroadUserBundle:FunGroup')->findAll();
	
	if (!$groups) {
	    throw $this->createNotFoundException('No found FUN groups! ');
	}
	
	return $this->render('AbroadUserBundle:FunGroup:list.html.twig', array (
	    'groups' => $groups
	    ));
	
    }
    
    public function showAction($id, $slug) {
	
	$em = $this->getDoctrine()->getEntityManager();
	
	$group = $em->getRepository('AbroadUserBundle:FunGroup')->find($id);
	
	if (!$group) {
	    throw $this->createNotFoundException('Unable to find any Fun Group!!!');
	}
	
	return $this->render('AbroadUserBundle:FunGroup:show.html.twig', array(
	    "group" => $group
	));
    }
    
    
}

