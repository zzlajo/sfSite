<?php

namespace Abroad\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     **/
    protected $myFriends;
    
    /**
     * @ORM\ManyToMany(targetEntity="Abroad\UserBundle\Entity\FunGroup", mappedBy="users")
     */
    protected $funGroups;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255,  nullable=true)
     */
    private $firstName;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="currentLocation", type="string", length=255,  nullable=true)
     */
    private $currentLocation;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bornLocation", type="string", length=255,  nullable=true)
     */
    private $bornLocation;

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
    	$this->myFriends->removeElement($user);
    }
    
    /**
     * @param  User $user
     * @return void
     */
    public function getFriends() {

		return $this->myFriends;
	
    }

    /**
     * @param  User $user
     * @return void
     */
    public function getFriendsWith() {

	return $this->friendsWithMe;
	
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

    /**
     * Get all friends as array
     * 
     * @return array
     */
    public function getAllFriends() {
    
    	$friends1 = $this->getFriends()->toArray();
		$friends2 = $this->getFriendsWith()->toArray();
	
		return array_merge($friends1, $friends2);
    
    }


    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set currentLocation
     *
     * @param string $currentLocation
     * @return User
     */
    public function setCurrentLocation($currentLocation)
    {
        $this->currentLocation = $currentLocation;

        return $this;
    }

    /**
     * Get currentLocation
     *
     * @return string 
     */
    public function getCurrentLocation()
    {
        return $this->currentLocation;
    }

    /**
     * Set bornLocation
     *
     * @param string $bornLocation
     * @return User
     */
    public function setBornLocation($bornLocation)
    {
        $this->bornLocation = $bornLocation;

        return $this;
    }

    /**
     * Get bornLocation
     *
     * @return string 
     */
    public function getBornLocation()
    {
        return $this->bornLocation;
    }

    /**
     * Add groups
     *
     * @param \FOS\UserBundle\Model\GroupInterface $groups
     * @return User
     */
    public function addGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \FOS\UserBundle\Model\GroupInterface $groups
     */
    public function removeGroup(\FOS\UserBundle\Model\GroupInterface $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add friendsWithMe
     *
     * @param \Abroad\UserBundle\Entity\User $friendsWithMe
     * @return User
     */
    public function addFriendsWithMe(\Abroad\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \Abroad\UserBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\Abroad\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriends
     *
     * @param \Abroad\UserBundle\Entity\User $myFriends
     * @return User
     */
    public function addMyFriend(\Abroad\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \Abroad\UserBundle\Entity\User $myFriends
     */
    public function removeMyFriend(\Abroad\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    /**
     * Add funGroups
     *
     * @param \Abroad\UserBundle\Entity\FunGroup $funGroups
     * @return User
     */
    public function addFunGroup(\Abroad\UserBundle\Entity\FunGroup $funGroups)
    {
        $this->funGroups[] = $funGroups;

        return $this;
    }

    /**
     * Remove funGroups
     *
     * @param \Abroad\UserBundle\Entity\FunGroup $funGroups
     */
    public function removeFunGroup(\Abroad\UserBundle\Entity\FunGroup $funGroups)
    {
        $this->funGroups->removeElement($funGroups);
    }

    /**
     * Get funGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFunGroups()
    {
        return $this->funGroups;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
