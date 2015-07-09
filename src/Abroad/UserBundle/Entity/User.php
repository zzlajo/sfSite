<?php

namespace Abroad\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
//    \Doctrine\Common\Collections\ArrayCollection();

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
  * @ORM\Entity(repositoryClass="Abroad\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", mappedBy="myFriends")
     */
    protected $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     **/
    protected $myFriends;
    
    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\FunGroup", mappedBy="funGroups")
     */
    protected $funGroups;

    public function __construct() {
	parent::__construct();
        $this->groups = new ArrayCollection();
	$this->friendsWithMe = new ArrayCollection();
	$this->myFriends = new ArrayCollection();
	$this->funGroups = new ArrayCollection();
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
     * @param  User $user
     * @return void
     */
    public function addFriend(User $user)
    {
        if (!$this->friends->contains($user)) {
            $this->friends->add($user);
            $user->addFriend($this);
        }
	return $this;
    }

    /**
     * @param  User $user
     * @return void
     */
    public function removeFriend(User $user)
    {
        if ($this->friends->contains($user)) {
            $this->friends->removeElement($user);
            $user->removeFriend($this);
        }
	return $this;
    }


//    public function __construct()
//    {
//        parent::__construct();
//        // your own logic
//    }
}