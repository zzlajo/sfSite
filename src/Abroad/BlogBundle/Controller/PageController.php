<?php

namespace Abroad\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Abroad\BlogBundle\Entity\Enquiry;
use Abroad\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    public function indexAction()
    {
	/*
	$em = $this -> getDoctrine () -> getManager (); 
$query = $em -> createQuery (
 'SELECT p
 FROM AppBundle:Product p 
WHERE p.price > :price 
ORDER BY p.price ASC'
 ) -> setParameter ( 'price' , '19.99' ); 

$products = $query -> getResult (); 
// to get just one result: 
// $product = $query->setMaxResults(1)->getOneOrNullResult();  
*/
	
	
	$em = $this->getDoctrine()->getEntityManager();
	
        $blogs = $em->getRepository('AbroadBlogBundle:Blog')
                    ->getLatestBlogs();
/////////// DQL
//	$blogs = $em -> createQuery (
//		'SELECT b
//		FROM AbroadBlogBundle:Blog b 
// 	        ORDER BY b.created DESC'
//		)-> getResult ();
//	$blogs = $query -> getResult (); 
	
///////////// QueryBuilder	
//	$blogs = $em->createQueryBuilder()
//		->select('b')
//		->from('AbroadBlogBundle:Blog', 'b')
//		->addOrderBy('b.created', 'DESC')
//		->getQuery()
//		->getResult();
        $user = $this->container->get('fos_user.user_manager')
				->findUserByUsername('zlajo');
//	var_dump($user); die;
	
	return $this->render('AbroadBlogBundle:Page:index.html.twig', array(
	    'blogs' => $blogs
	));
    }
    
    /**
     * @Route("/about", name="AbroadBlogBundle_about")
     */
    public function aboutAction() 
    {
	return $this->render('AbroadBlogBundle:Page:about.html.twig');	
    }
    

    
    public function contactAction() {
	$enquiry = new \Abroad\BlogBundle\Entity\Enquiry();
	$form = $this->createForm(new \Abroad\BlogBundle\Form\EnquiryType(), $enquiry);
	
	$request = $this->getRequest();
	if ($request->getMethod() == 'POST') {
	    $form->bind($request);
	    
	    if($form->isValid()){
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($enquiry);
		$em->flush();
		// Perform some action, such as sending an email

		// Redirect - This is important to prevent users re-posting
		// the form if they refresh the page		

		$message = \Swift_Message::newInstance()
		    ->setSubject('Kontakt enquiry from symblog')
		    ->setFrom('mirkan.peric@gmail.com')
//		    ->setTo('zuber.zlajo@gmail.com')
		    ->setTo($this->container->getParameter('abroad_blog.emails.contact_email'))
		    ->setBody($this->renderView('AbroadBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
		$this->get('mailer')->send($message);
	
//		$this->get('session')->setFlash('abroad-notice', 'Your contact enquiry was successfully sent. Thank you!');
		$this->get('session')->getFlashBag()->add('Abroad-notice','Your contact enquiry was successfully sent. Thank you!');

		// Redirect - This is important to prevent users re-posting
		// the form if they refresh the page
		return $this->redirect($this->generateUrl('AbroadBlogBundle_contact'));
   
	    }
	}	
	return $this->render('AbroadBlogBundle:Page:contact.html.twig', array(
	    'form'=>$form->createView()
	));
    }
    
//    public function sidebarAction() {
//	
//	$em = $this->getDoctrine()->getEntityManager();
//	
//	$tags = $em->getRepository('AbroadBlogBundle:Blog')
//		    ->getTags();
//	
//	$tagWeights = $em->getRepository('AbroadBlogBundle:Blog')
//		    ->getTagWeights($tags);
//	
//	return $this->render('AbroadBlogBundle:Page:sidebar.html.twig', array(
//	   'tags' => $tagWeights
//	));
//	
//    }
    
public function sidebarAction()
{
    $em = $this->getDoctrine()
               ->getEntityManager();

    $tags = $em->getRepository('AbroadBlogBundle:Blog')
               ->getTags();

    $tagWeights = $em->getRepository('AbroadBlogBundle:Blog')
                     ->getTagWeights($tags);
//	    var_dump($tagWeights); die;
    
    $commentLimit = $this->container
			->getParameter('abroad_blog.comments.latest_comment_limit');
    
    
    $latestComments = $em->getRepository('AbroadBlogBundle:Comment')
			->getLatestComments($commentLimit);

    return $this->render('AbroadBlogBundle:Page:sidebar.html.twig', array(
	'latestComments' => $latestComments,
        'tags' => $tagWeights
    ));
}
    
    
}
