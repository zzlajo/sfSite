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

//    /**
//     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\Group", inversedBy="users")
//     * @ORM\JoinTable(name="fos_user_user_group",
//     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
//     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
//     * )
//     */
    /**
     *
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\Group", mappedBy="users")
     */
    protected $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", mappedBy="myFriends")
     */
    protected $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\User", inversedBy="friendsWithMe", cascade={"persist","remove"})
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id", onDelete="cascade", onDelete="CASCADE")}
     *      )
     **/
    protected $myFriends;
    
    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\FunGroup", mappedBy="users")
     */
    protected $funGroups;

    public function __construct() {
	parent::__construct();
        $this->groups = new ArrayCollection();
	$this->friendsWithMe = new ArrayCollection();
	$this->myFriends = new ArrayCollection();
	$this->funGroups = new ArrayCollection();
	$this->roles = new ArrayCollection();
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
    {	// provera da li postoji u bazi vec dodat user u myFriedns ili friendsWithMe u user
        if (!$this->myFriends->contains($user) && !$this->friendsWithMe->contains($user)) {
            $this->myFriends->add($user);
            $user->addFriend($this);
        }
	return $this;
    }
//    public function addFriend(User $user)
//    {
//        if (!$this->getFrineds()->contains($user)) {
//            $this->getFrineds()->add($user);
//        }
//
//        return $this;
//    }
//public function addFriend(User $user)
//{
//    $this->myFriends[] = $user;
//    $user->myFriends[] = $this; // php allows to access private members of objects of the same type
//            return $this;
//
//}
    /**
     * @param  User $user
     * @return void
     */
    public function removeFriend(User $user)
    {

//	    $aaa = $this->myFriends->getValues($user);		    
	$this->myFriends->removeElement($user);
	    
//$aa = $this->friendsWithMe->getValues($user);		    

//	    $myF = $this->friendsWithMe->removeElement($user);

//        if ($this->myFriends->contains($user)) {
//            $this->myFriends->removeElement($user);
//            $user->removeFriend($this);
//	    echo " 111111111111 <br>";
//        } 
//	elseif ($this->friendsWithMe->contains($user)) {
//            $this->friendsWithMe->removeElement($user);
//            $user->removeFriend($this);
//	}
//	
//	return $this;
//    public function removeUser(\Club\UserBundle\Entity\User $users)
//    {
//        $this->users->removeElement($users);
//    }
    }
    
    /**
     * @param  User $user
     * @return void
     */
    public function getFriends() {

	return $this->myFriends->toArray();
	
    }

    /**
     * @param  User $user
     * @return void
     */
    public function getFriendsWith() {

	return $this->friendsWithMe->toArray();
	
    }

    
    public function isFriend(User $user)
    {	// provera da li postoji u bazi vec dodat user u myFriedns ili friendsWithMe u user
        if ($this->myFriends->contains($user) || $this->friendsWithMe->contains($user)) {
	    return TRUE;
	}
    }
//    public function getFrineds()
//    {
//        return $this->myFriends ?: $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
//    }
    
    /**
     * Gets the groups granted to the user.
     *
     * @return Collection
     */
    // ovo sam omogucio pa onemogucio kad sam imao problema prilikom logovanja vendor/friendsofsymfony/user-bundle/Model/User.php Warning: array_merge(): Argument #2 is not an array
//    public function getGroups()
//    {
////        return $this->groups ?: $this->groups = new ArrayCollection();
//        return $this->groups = new ArrayCollection();
//    }

//        public function addFunGroups($funGroup) {
//
//		$this->funGroups[] = $funGroup;
//
//    }

//    public function __construct()
//    {
//        parent::__construct();
//        // your own logic
//    }
}