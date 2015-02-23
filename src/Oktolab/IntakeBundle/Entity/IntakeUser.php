<?php

namespace Oktolab\IntakeBundle\Entity;

use Bprs\UserBundle\Entity\BprsUserInterface;
use Bprs\UserBundle\Entity\User as BprsUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * IntakeUser
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class IntakeUser implements BprsUserInterface
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
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="users")
     * @ORM\JoinTable(name="intakeUsers_contacts")
     */
    private $contacts;

    /**
     * @ORM\OneToOne(targetEntity="Bprs\UserBundle\Entity\User", inversedBy="extendedUser")
     * @ORM\JoinColumn(name="BprsUser_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $baseUser;

    public function __construct() {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getBaseUser()
    {
        return $this->baseUser;
    }

    public function setBaseUser(BprsUser $user)
    {
        $this->baseUser = $user;
        return $this;
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function addContact($contact)
    {
        $this->contacts[] = $contact;
    }

    public function removeContact($contact)
    {
        $this->contacts->removeElement($contact);
    }
}