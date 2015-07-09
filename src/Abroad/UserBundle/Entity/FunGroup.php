<?php

namespace Abroad\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * FunGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Abroad\UserBundle\Entity\FunGroupRepository")
 */
class FunGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    
    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="fun_group",
     *	    joinColumns={@ORM\JoinColumn(name="fun_group_id", referencedColumnName="id")},
     *	    inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */

    private $users;
    
    /**
     * 
     * @var string
     * 
     * @ORM\Column(name="slug", type="string")
     */
    private $slug;

    
    
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add user
     *
     * @param \Abroad\UserBundle\Entity\FunGroup $user
     * @return FunGroup
     */
    public function addUser($user) {
//	if (!$this->getUsers()->contains($user)) {
//	    $this->getUsers()->add($user);
//	}
	$this->users[] = $user;

	return $this;
    }

    public function getUsers() {
	
	return $this->users ? : $this->users = new \Doctrine\Common\Collections\ArrayCollection();
	
    }
    
    public function setUsers(Collection $users)
    {
        $this->users= $users;

        return $this;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FunGroup
     */
    public function setName($name)
    {
        $this->name = $name;
	$this->setSlug($this->name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return FunGroup
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set slug
     * 
     * @param string $slug
     * @return FunGroup
     */
    public function setSlug($slug) {
	
	$this->slug = $this->slugify($slug);
	
	return $this;
	
    }
    
    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    
    
    public function slugify($text)
    {
	// replace non letter or digits by -
	$text = preg_replace('#[^\\pL\d]+#u', '-', $text);

	// trim
	$text = trim($text, '-');

	// transliterate
	if (function_exists('iconv'))
	{
	    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	}

	// lowercase
	$text = strtolower($text);

	// remove unwanted characters
	$text = preg_replace('#[^-\w]+#', '', $text);

	if (empty($text))
	{
	    return 'n-a';
	}

	return $text;
    }

    
}
