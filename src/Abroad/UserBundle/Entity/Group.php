<?php

namespace Abroad\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table()
 * @ORM\Table(name="fos_group")
 * @ORM\Entity(repositoryClass="Abroad\UserBundle\Entity\GroupRepository")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     /**
      * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", inversedBy="groups")
      * @ORM\JoinTable(name="fos_user_user_group",
      *	    joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
      *	    inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
      * )
      */
//    /**
//     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", mappedBy="groups")
//     */
     protected $users;


    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
	$this->roles = array();
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
    
    public function addUser($user)
    {
        if (!$this->getUsers()->contains($user)) {
            $this->getUsers()->add($user);
        }

        return $this;
    }


    public function getUsers()
    {
        return $this->users ?: $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
}
