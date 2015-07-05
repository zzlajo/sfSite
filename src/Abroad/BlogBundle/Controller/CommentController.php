<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Abroad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Abroad\BlogBundle\Entity\Comment;
use Abroad\BlogBundle\Form\CommentType;

/**
 * Comment controller
 */

class CommentController extends Controller {
    
    public function newAction($blog_id) {
	
	$blog = $this->getBlog($blog_id);
	
	$comment = new Comment();
	$comment->setBlog($blog);
	$form = $this->createForm(new CommentType(), $comment);
	
	return $this->render('AbroadBlogBundle:Comment:form.html.twig', array (
	    'comment'=>$comment,
	    'form'=> $form->createView()
	));
		
	
    }
    
    public function createAction($blog_id) {
	
	$blog = $this->getBlog($blog_id);
	
	$comment = new Comment();
	$comment->setBlog($blog);
	$request = $this->getRequest();
	$form = $this->createForm(new CommentType(), $comment);
	$form->bind($request);
	

        if ($form->isValid()) {
            // TODO: Persist the comment entity
	    $em = $this->getDoctrine()->getEntityManager();
	    $em->persist($comment);
	    $em->flush();

//            return $this->redirect($this->generateUrl('AbroadBlogBundle_blog_show', array(
//                'id' => $comment->getBlog()->getId())) .
//                '#comment-' . $comment->getId()
//            );
	    return $this->redirect($this->generateUrl('AbroadBlogBundle_blog_show', array(
		'id'    => $comment->getBlog()->getId(),
		'slug'  => $comment->getBlog()->getSlug())) .
		'#comment-' . $comment->getId()
	    );
        }

        return $this->render('AbroadBlogBundle:Comment:create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
	
    }
    
    protected function getBlog($blog_id) {
	$em = $this->getDoctrine()->getEntityManager();
	
        $blog = $em->getRepository('AbroadBlogBundle:Blog')->find($blog_id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $blog;
    }
    
}


