<?php

namespace Abroad\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

use Abroad\UserBundle\Entity\User;

/**
 * Friends controller.
 */
class FriendsController extends Controller {
    
    public function addFriendAction ($id, $text='Insert Link') {

//	var_dump($id); 
//	die;
//	
//	$currentUser = $this->getUser();
//	return true;

    $output = '<a href="'.'AbroadUserBundle:Friends:listUsers'.'" ';
    $output .= '>' . $text . '</a>';
//    $response->setContent($output);
//    $response->setStatusCode(200);
//    $response->headers->set('Content-Type', 'text/html');
//    return $response;
     return new Response($output);
	
    }
    
    public function listUsersAction( ) {
	$user = $this->getUser();
	$cId = $user->getId();

	$usersAll = $this->getDoctrine()->getRepository('AbroadUserBundle:User')->getUsersWithoutCurrent($cId);
		
	if (!$usersAll) {
	    throw $this->createNotFoundException('Unable to find any user!');
	}
	
	return $this->render('AbroadUserBundle:Friends:list_users.html.twig', array (
	    'usersAll' => $usersAll,
	));
	
	
    }
    
}
