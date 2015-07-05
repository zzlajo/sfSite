<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Abroad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * Show blog entry
     */
    public function showAction($id, $slug) {
	 $em = $this->getDoctrine()->getEntityManager();
	
	$blog = $em->getRepository('AbroadBlogBundle:Blog')->find($id);
	
	if (!$blog) {
	    throw $this->createNotFoundException('Unable to find Blog post :(((( ');
	}
	
	$comments = $em->getRepository('AbroadBlogBundle:Comment')
			->getCommentsForBlog($blog->getId());
	
	return $this->render('AbroadBlogBundle:Blog:show.html.twig', array(
	    'blog' => $blog,
	    'comments' => $comments,
	));
	
    }
}