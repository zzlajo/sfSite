<?php

namespace Abroad\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\GroupController as BaseController;
use Symfony\Component\HttpFoundation\Request;

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

        $group = $groupManager->createGroup('');
	

        $form = $formFactory->createForm();
        $form->setData($group);

	$form->handleRequest($request);
        if ($form->isValid()) {
	    $gName = $group->getName();
	    $user1 = $this->getUser()->getId();
	    var_dump($user1);
	    $gId = $this->findGroupBy('name', $gName);
	    var_dump($gId);
	    die;
	    
	}
	

//$groupEntity = $this->getGroup($groupId);
    }
////    public function createAction($blog_id) {
////	
////	$blog = $this->getBlog($blog_id);
////	
////	$comment = new Comment();
////	$comment->setBlog($blog);
////	$request = $this->getRequest();
////	$form = $this->createForm(new CommentType(), $comment);
////	$form->bind($request);
////	
////
////        if ($form->isValid()) {
////            // TODO: Persist the comment entity
////	    $em = $this->getDoctrine()->getEntityManager();
////	    $em->persist($comment);
////	    $em->flush();
////
//////            return $this->redirect($this->generateUrl('AbroadBlogBundle_blog_show', array(
//////                'id' => $comment->getBlog()->getId())) .
//////                '#comment-' . $comment->getId()
//////            );
////	    return $this->redirect($this->generateUrl('AbroadBlogBundle_blog_show', array(
////		'id'    => $comment->getBlog()->getId(),
////		'slug'  => $comment->getBlog()->getSlug())) .
////		'#comment-' . $comment->getId()
////	    );
////        }
////
////        return $this->render('AbroadBlogBundle:Comment:create.html.twig', array(
////            'comment' => $comment,
////            'form'    => $form->createView()
////        ));
////	
////    }
//    
//	
//	
//    }

    
}
