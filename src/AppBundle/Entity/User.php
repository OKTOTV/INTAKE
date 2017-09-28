<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity\User as BaseUser;

/**
 * User
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bprs\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser {

    /**
     * @ORM\ManyToMany(targetEntity="Contact", inversedBy="features")
     */
    private $contacts;

    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    public function addContact($contact)
    {
        $this->contacts[] = $contact;
        return $this;
    }

    public function removeContact($contact)
    {
        $this->contacts->removeElement($contact);
        return $this;
    }

    public function getContacts()
    {
        return $this->contacts;
    }
}
