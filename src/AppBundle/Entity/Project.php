<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A new project with description, files (assets) and additional informations
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Project {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Asset", mappedBy="project")
     */
    private $assets;

    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="projects")
     */
    private $contact;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
    * @var string
    * @Assert\Length(max = 950)
    * @ORM\Column(name="description", type="text", length=950, nullable=true)
    */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank(message = "intake.contact.name_notblank" )
     * @Assert\Email(checkMX = true)
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * Date of creation the entry in the database. Not of the project!
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    public function __toString()
    {
        return $title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function getAssets()
    {
        return $this->assets;
    }

    public function setAssets($assets = null)
    {
        $this->assets = $assets;
        return $this;
    }

    public function addAsset($asset)
    {
        $this->assets[] = $asset;
        return $this;
    }

    public function removeAsset($asset)
    {
        $this->assets->removeElement($asset);
        return $this;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->created_at = new \DateTime();
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
