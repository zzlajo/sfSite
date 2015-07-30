<?php

namespace Abroad\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Abroad\UserBundle\Entity\InvitationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Invitation
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
     * @var integer
     *
     * @ORM\Column(name="senderId", type="integer")
     */
    private $senderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="recipientId", type="integer")
     */
    private $recipientId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
     * Set senderId
     *
     * @param integer $senderId
     * @return Invitation
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;

        return $this;
    }

    /**
     * Get senderId
     *
     * @return integer 
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Set recipientId
     *
     * @param integer $recipientId
     * @return Invitation
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Get recipientId
     *
     * @return integer 
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Invitation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @ORM\PrePersist
     */
    public function setCreated()
    {
        $this->created = new \DateTime();

//        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    public function __toString()
    {
	return ('Invatation sent.');
    }

}
