<?php

namespace Oktolab\IntakeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity\User as BaseUser;
/**
 * IntakeUser
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bprs\UserBundle\Entity\UserRepository")
 */
class IntakeUser extends BaseUser
{
    /**
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="users")
     * @ORM\JoinTable(name="intakeUsers_contacts")
     */
    private $contacts;

    public function __construct() {
        parent::__construct();
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
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
