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
    
    public function addFriendAction ($id) {

	var_dump($id); 
	$em = $this->getDoctrine()->getEntityManager();
	
	$userFriend = $em->getRepository('AbroadUserBundle:User')->find($id);
	
	$user = $this->getUser();
	
	if(!$user) {
	    throw $this->createNotFoundException('The user which you want to add on your friends, it doesn`t exist :( ');
	}
	$friend = $user->addFriend($userFriend);


    try {
	$em->persist($user);
	$em->flush($user); 
    } catch(\Doctrine\DBAL\DBALException $e)
    {
	throw $e;
//	echo $e->getMessage(),"<br>";
//	echo  $e->getCode(),"<br>";
    }

	return new Response($friend);
	
    }

    public function removeFriendAction ($id) {

	var_dump($id); 
	$em = $this->getDoctrine()->getEntityManager();
	
	$userFriend = $em->getRepository('AbroadUserBundle:User')->find($id);
	$friendId = $userFriend->getId();
	
	$user = $this->getUser();
	$userId = $user->getId();
	
	if(!$user) {
	    throw $this->createNotFoundException('The user which you want to remove from your friends, it doesn`t exist :( ');
	}
//	$friend = $user->removeFriend($userFriend);
	
	$removeF = $this->getDoctrine()->getRepository('AbroadUserBundle:User')->removeRelatedFriend($userId, $friendId);
        
//	$em = $this->getDoctrine()->getManager();
//        $user = $em->find('ClubUserBundle:User', $user_id)
//	$group->removeUser($user);
//        $em->persist($group);
//        $em->flush();



    try {
	$em->persist($user);
	$em->flush($user); 
    } catch(\Doctrine\DBAL\DBALException $e)
    {
	throw $e;
//	echo $e->getMessage(),"<br>";
//	echo  $e->getCode(),"<br>";
    }

	return new Response($friend);
	
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
