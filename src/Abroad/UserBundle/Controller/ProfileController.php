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

use FOS\UserBundle\Controller\ProfileController as BaseController;



class ProfileController extends BaseController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
	
	return $this->render('AbroadUserBundle:Profile:show.html.twig', array(
//        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user,
	    'addFriend' => FALSE
        ));

    }
    
    /**
     * Show required user
     */
    
    public function showOneAction($id, $flash = FALSE, Request $request) {

	$user = $this->getDoctrine()->getRepository('AbroadUserBundle:User')->find($id);
	if (!$user && !is_object($user)) {
	    throw $this->createNotFoundException(' User not found! ');
	}
	$userCurrent = $this->getUser();
	$userCurrentId = $userCurrent->getId();

	$friends = $userCurrent->isFriend($user);
	
	$flash = $request->query->get('flash') == TRUE ? $this->get('session')->getFlashBag()->add('Friend-notice','You sent to '.$user->getUsername().' friend request successfully!') : false ;


	if ($id == $userCurrentId) {
	    return $this->render('AbroadUserBundle:Profile:show.html.twig', array(
		'user' => $user,
		'addFriend' => FALSE,
		'removeFriend' => FALSE
	    ));	    
	} elseif ($userCurrent->isFriend($user)) {
	    return $this->render('AbroadUserBundle:Profile:show.html.twig', array(
		'user' => $user,
		'addFriend' => FALSE,
		'removeFriend' => TRUE
	    ));	    
	} else {
	    return $this->render('AbroadUserBundle:Profile:show.html.twig', array(
		'user' => $user,
		'addFriend' => TRUE,
		'removeFriend' => FALSE
	    ));
	}
	
    }

  
}
