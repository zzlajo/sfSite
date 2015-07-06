<?php

namespace Abroad\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\GroupController as BaseController;
use Symfony\Component\HttpFoundation\Request;


use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterGroupResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseGroupEvent;
use FOS\UserBundle\Event\GroupEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class GroupController extends BaseController
{
    /**
     * Show all groups
     */
    public function listAction()
    {
        $groups = $this->get('fos_user.group_manager')->findGroups();

        return $this->render('AbroadUserBundle:Group:list.html.twig', array(
            'groups' => $groups
        ));
//        return $this->render('AbroadUserBundle:Group:list.html.twig', array('name' => $name));

    }
    
    /** 
     * Show one group
     */
    public function showAction($groupName)
    {
        $group = $this->findGroupBy('name', $groupName);

        return $this->render('FOSUserBundle:Group:show.html.twig', array(
            'group' => $group
        ));
    }
    
    public function newAction(Request $request)
    {
        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
        $groupManager = $this->get('fos_user.group_manager');
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.group.form.factory');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $group = $groupManager->createGroup('');
	
        $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_INITIALIZE, new GroupEvent($group, $request));

        $form = $formFactory->createForm();
        $form->setData($group);

	$form->handleRequest($request);
        if ($form->isValid()) {
	    
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_SUCCESS, $event);
	    
//	    var_dump($group);
//	    var_dump($group-> getName());
//	    die;
	    $nesto = $group->addUser($this->getUser());
//	    var_dump($nesto);
//	    die;
            $groupManager->updateGroup($group);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_group_show', array('groupName' => $group->getName()));
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

	    return $response;
        }

        return $this->render('FOSUserBundle:Group:new.html.twig', array(
            'form' => $form->createview(),
        ));
	
//        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
//        $groupManager = $this->get('fos_user.group_manager');
//        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
//        $formFactory = $this->get('fos_user.group.form.factory');
//
//        $group = $groupManager->createGroup('');
//	
//
//        $form = $formFactory->createForm();
//        $form->setData($group);
//
//	$form->handleRequest($request);
//        if ($form->isValid()) {
//	    $gName = $group->getName();
//	    $user1 = $this->getUser()->getId();
//	    var_dump($user1);
//	    $gId = $this->findGroupBy('name', $gName);
//	    var_dump($gId);
//	    die;
//	    
//	}
	

//$groupEntity = $this->getGroup($groupId);
    }


    
}
