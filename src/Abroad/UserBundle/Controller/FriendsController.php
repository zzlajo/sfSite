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
use Abroad\UserBundle\Entity\Invitation;


/**
 * Friends controller.
 */
class FriendsController extends Controller {
    
    public function setFriendInvitationAction($id) {
	
	$em = $this->getDoctrine()->getManager();
	
	$userFriend = $em->getRepository('AbroadUserBundle:User')->find($id);
	if(!$userFriend) {
	    throw $this->createNotFoundException('The user which you want to add on your friends, it doesn`t exist :( ');
	}
	$recipientId = $userFriend->getId();
		
	$user = $this->getUser();
	$senderId = $user->getId();
	
	// user can not sent invitation to himself
	if($senderId === $recipientId) {
	    throw $this->createNotFoundException(' User can not sent invitation to himself! ');
	}

	$isInvited = $em->getRepository('AbroadUserBundle:Invitation')->isInvited($senderId, $recipientId);

	if($isInvited) {
	    throw $this->createNotFoundException(' Invitation sent! ');
	}
	
	$invitation = new Invitation();
	$invitation->setSenderId($senderId);
	$invitation->setRecipientId($recipientId);


	try {
	    $em->persist($invitation);
	    $em->flush($invitation); 
	} catch(\Doctrine\DBAL\DBALException $e)
	{
	    throw $e;
    //	echo $e->getMessage(),"<br>";
    //	echo  $e->getCode(),"<br>";
	}

//	$this->get('session')->getFlashBag()->add('Friend-notice','You sent to '.$userFriend->getUsername().' friend request successfully!');
return $this->redirect($this->generateUrl('abroad_user_profile_show', array('id' => $id, 'flash' => TRUE)));

//$response = $this -> forward ( 'AbroadUserBundle:Profile:showOne' , array ( 
//'id' => $id , 
//'flash' => TRUE , )); 
//
//	return $response;
	
//	return $this -> redirectToRoute ( 'homepage' ); 
//	$referer = $this->getRequest()->headers->get('referer');
//	if ($referer == NULL) {
//	    $url = $this->router->generate('fallback_url');
//	} else {
//	    $url = $referer;
//	}
//	    return $this->redirect($referer);
	
    }
    
    public function getFriendInvitationAction() {
	
	$user = $this->getUser();
	$recipientId = $user->getId();
	
	$em = $this->getDoctrine()->getEntityManager();
	
	$listInvitations = $em->getRepository('AbroadUserBundle:Invitation')->checkInvitations($recipientId);
	
	var_dump($listInvitations);
	die;
	
    }
    
    public function listFriendInvitationsAction() {
	
	$user = $this->getUser();
	$recipientId = $user->getId();
//	var_dump($recipientId);
	
	$em = $this->getDoctrine()->getManager();
	
	$listInvitations = $em->getRepository('AbroadUserBundle:Invitation')->checkInvitations($recipientId);
	
//	var_dump($listInvitations);
//	die;
	
//    	return new Response($listInvitations);
	
	    return $this->render('AbroadUserBundle:Friends:list_invitations.html.twig', array (
		'invatations'=>$listInvitations,
	    ));

	
    }

    public function acceptFriendInvitationAction($senderId) {
	
	$user = $this->getUser();
	$recipientId = $user->getId();
	
	$em = $this->getDoctrine()->getManager();
	$acceptInvitation = $em->getRepository('AbroadUserBundle:Invitation')->acceptInvitation($senderId, $recipientId);
//	$acceptInvitation = false;

        if (!$acceptInvitation) {
            throw new AccessDeniedException('This user did not send friend invitation to you.');
        }

	    $addFriend = $this->addFriendAction($senderId);

//	if ($acceptInvitation) {
//	    $addFriend = $this->addFriendAction($senderId);
//	}
//    try {
//	$acceptInvitation = $em->getRepository('AbroadUserBundle:Invitation')->acceptInvitation($senderId, $recipientId);
////        $addFriend = $this->addFriendAction($senderId);
//    } catch(\Doctrine\DBAL\DBALException $e)
//    {
//	throw $e; 
//    }	
	$referer = $this->getRequest()->headers->get('referer');
	if ($referer == NULL) {
	    $url = $this->router->generate('fallback_url');
	} else {
	    $url = $referer;
	}
	    return $this->redirect($referer);
//    	return new Response($acceptInvitation);

    }
    
    public function addFriendAction ($id) {

	var_dump($id); 
	$em = $this->getDoctrine()->getEntityManager();
	
	$userFriend = $em->getRepository('AbroadUserBundle:User')->find($id);
	$recipientId = $userFriend->getId();
		
	$user = $this->getUser();
	$senderId = $user->getId();
	
	
	if(!$userFriend) {
	    throw $this->createNotFoundException('The user which you want to add on your friends, it doesn`t exist :( ');
	}
	// user can not sent invitation to himself
	elseif($senderId === $recipientId) {
	    throw $this->createNotFoundException(' User can not sent invitation to himself! ');
	}

	
//	$friend = $user->addFriend($userFriend);
	$friend = $userFriend->addFriend($user);

	try {
	    $em->persist($friend);
	    $em->flush($friend); 
	} catch(\Doctrine\DBAL\DBALException $e)
	{
	    throw $e;
    //	echo $e->getMessage(),"<br>";
    //	echo  $e->getCode(),"<br>";
	}
//	$this->get('session')->getFlashBag()->add('AddFriend-notice','You sent to '.$userFriend->getUsername().' friend request successfully!');
	$this->get('session')->getFlashBag()->add('AddFriend-notice','You and '.$userFriend->getUsername().' are NOW friends!');


	$referer = $this->getRequest()->headers->get('referer');
	if ($referer == NULL) {
	    $url = $this->router->generate('fallback_url');
	} else {
	    $url = $referer;
	}
    //	return new Response($invitation);
	    return $this->redirect($referer);
	    
//	return new Response($friend);
	
    }

    public function removeFriendAction ($id) {

    	$user = $this->getUser();
    	
    	// if user not loged in redirect to login page
    	if (!$user) {
    		return $this->redirect('login');
    	}
    	
		$em = $this->getDoctrine()->getEntityManager();
		
		$userFriend = $em->getRepository('AbroadUserBundle:User')->find($id);
		
		if(!$userFriend) {
		    throw $this->createNotFoundException('The user which you want to remove from your friends, it doesn`t exist :( ');
		}
		
		$user->removeFriend($userFriend);
		
		$em->persist($user);
		$em->flush($user);
		
		$this->addFlash('notice', 'You are remove '.$userFriend->getUsername().' from your friends!');
		
		return $this->redirectToRoute('abroad_my_friends');
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// 
//$emm = $this->getDoctrine()->getEntityManager();
//$query = $emm->createQuery(
//    'DELETE FROM AbroadUserBundle:Friends f WHERE f.user_id = :userId AND f.friend_user_id = :friendId) OR '
//						. '(f.friend_user_id = :userId AND f.user_id = :friendId)'
//)->setParameter('userId', $userId)->setParameter('friendId', $friendId);
//
//$query->execute();


//		->where('(f.user_id = :userId AND f.friend_user_id = :friendId) OR '
//		      . '(f.friend_user_id = :userId AND f.user_id = :friendId) ')
//		->setParameter('userId', $userId)
//		->setParameter('friendId', $friendId);
//		


//	echo '<pre> 11111111111 myFriends $user ';
//	\Doctrine\Common\Util\Debug::dump($user->myFriends);
//	echo '</pre>';
//	echo '<pre> 22222222222 friendsWithMe $user ';
//	\Doctrine\Common\Util\Debug::dump($user->friendsWithMe);
//	echo '</pre>';
//
//	echo '<pre> 11111111111 myFriends $userFriend ';
//	\Doctrine\Common\Util\Debug::dump($userFriend->myFriends);
//	echo '</pre>';
//	echo '<pre> 22222222222 friendsWithMe $userFriend ';
//	\Doctrine\Common\Util\Debug::dump($userFriend->friendsWithMe);
//	echo '</pre>';
//die;

	$friend = $user->removeFriend($userFriend);
//	$friendd = $userFriend->removeFriend($user);

//	$removeF = $this->getDoctrine()->getRepository('AbroadUserBundle:User')->removeRelatedFriend($userId, $friendId);
        
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
		$this->get('session')->getFlashBag()->add('UnFriend-notice','You are remove '.$userFriend->getUsername().' from your friends!');

	$referer = $this->getRequest()->headers->get('referer');
	if ($referer == NULL) {
	    $url = $this->router->generate('fallback_url');
	} else {
	    $url = $referer;
	}
	return $this->redirect($referer);
//	return new Response($friend);
	
    }

    
    public function listFriendsAction() {
	$user = $this->getUser();
	
	// if user not loged in redirect to login page
	if (!$user) {
		return $this->redirect('login');
	}
	
//	$userId = $user->getId();
	$friends1 = $user->getFriends();
	$friends2 = $user->getFriendsWith();
	
	$friends = array_merge($friends1, $friends2);
	
	return $this->render('AbroadUserBundle:Friends:list_friends.html.twig', array (
	    'friends'=>$friends,
	));
//	$this->getDoctrine()->getRepository('AbroadUserBundle:User')->
	
    }
    
    public function listUsersAction( ) {
	$user = $this->getUser();
	$cId = $user->getId();
	
	$usersAll = $this->getDoctrine()->getRepository('AbroadUserBundle:User')->getUsersWithoutCurrent($cId);

	
	$listInvitations = $this->getDoctrine()->getRepository('AbroadUserBundle:Invitation')->checkInvitations($cId);
	
	if (!$usersAll) {
	    throw $this->createNotFoundException('Unable to find any user!');
	}
	
	return $this->render('AbroadUserBundle:Friends:list_users.html.twig', array (
	    'usersAll' => $usersAll,
	    'invatations'=>$listInvitations,
	));
	
	
    }
    
}
