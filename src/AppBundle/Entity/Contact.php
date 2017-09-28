<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Contact {

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
    * @ORM\Column(name="sortnumber", type="integer")
    */
    private $sortNumber;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="contact")
     */
    private $projects;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max = 100)
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(message = "intake.contact.name_notblank" )
     * @Assert\Email(checkMX = true)
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    public function __construct() {
        $this->projects = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setSortNumber($number)
    {
        $this->sortNumber = $number;
        return $this;
    }

    public function getSortNumber()
    {
        return $this->sortNumber;
    }

    public function setProjects($projects)
    {
        $this->projects = $projects;
        return $this;
    }

    public function addProject($project)
    {
        $this->projects[] = $project;
        return $this;
    }

    public function removeProject($project)
    {
        $this->projects->removeElement($project);
        return $this;
    }

    public function getProjects()
    {
        return $this->projects;
    }
}
