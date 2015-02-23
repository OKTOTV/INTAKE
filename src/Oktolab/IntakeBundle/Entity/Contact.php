<?php

namespace Oktolab\IntakeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Contact
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
     * @var string
     * @Assert\NotBlank(message = "intake.contact.name_notblank" )
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "intake.file.contact.name_lengthMax"
     * )
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(message = "intake.contact.email_notblank" )
     * @Assert\Email(
     *     message = "intake.contact.email_wrong",
     *     checkMX = true 
     * )
     * 
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var integer
     * @ORM\Column(name="ordernumber", type="integer", nullable=true)
     */
    private $order;

    /**
     * @var array
     * @ORM\OneToMany(targetEntity="File", mappedBy="contact")
     */
    private $files;

    /**
     * @ORM\ManyToMany(targetEntity="IntakeUser", mappedBy="contacts")
     */
    private $users;

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Contact
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
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Add file
     *
     * @param  \Oktolab\IntakeBundle\Entity\Source $file
     * @return File
     */
    public function addFile(\Oktolab\IntakeBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Oktolab\IntakeBundle\Entity\Source $source
     */
    public function removeFile(\Oktolab\IntakeBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
