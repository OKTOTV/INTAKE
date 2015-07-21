<?php

namespace Oktolab\IntakeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\IntakeBundle\Entity\Contact;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * File
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity
 */
class File
{
    public function __construct()
    {
        $this->uniqueID = uniqid();
    }

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
     *
     * @Assert\NotBlank(message = "intake.file.series.notblank" )
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "intake.file.series.lengthMax"
     * )
     * @ORM\Column(name="series", type="string", length=100, nullable=true)
     */
    private $series;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "intake.file.episode_description.notblank" )
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "intake.file.episode_description.lengthMax"
     * )
     * @ORM\Column(name="episodeDescription", type="string", length=1000, nullable=true)
     */
    private $episodeDescription;

    /**
     * @var bool
     * @Assert\True(message = "intake.file.readAGB.notFalse")
     */
    private $readAGB;

    /**
     * @var  string
     *
     * @ORM\Column(name="uniqueID", type="string", length=13)
     */
    private $uniqueID;

    /**
     * var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Source", mappedBy="file")
     */
    private $sources;

    /**
     * @var integer
     * @Assert\NotNull(message = "intake.file.choose_a_contact")
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="files")
     */
    private $contact;

    /**
     * @Assert\NotBlank(message = "intake.file.uploaderEmail.notblank")
     * @Assert\Email(
     *     message = "intake.file.email_wrong",
     *     checkMX = true
     * )
     * @ORM\Column(name="uploaderEmail", type="string", length=255, nullable=true)
     * @var string
     */
    private $uploaderEmail;

    /**
    * @ORM\Column(name="createdAt", type="datetime")
    */
    private $createdAt;

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
     * Set series
     *
     * @param string $series
     * @return File
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set episodeDescription
     *
     * @param string $episodeDescription
     * @return File
     */
    public function setEpisodeDescription($episodeDescription)
    {
        $this->episodeDescription = $episodeDescription;

        return $this;
    }

    /**
     * Get episodeDescription
     *
     * @return string
     */
    public function getEpisodeDescription()
    {
        return $this->episodeDescription;
    }

    /**
     * Set readAGB
     *
     * @param bool $readAGB
     * @return File
     */
    public function setReadAGB($readAGB)
    {
        $this->readAGB = $readAGB;

        return $this;
    }

    /**
     * Get readAGB
     *
     * @return bool
     */
    public function getReadAGB()
    {
        return $this->readAGB;
    }

    /**
     * Set uniqueID
     *
     * @param string $uniqueID
     * @return File
     */
    public function setUniqueID($uniqueID)
    {
        $this->uniqueID = $uniqueID;

        return $this;
    }

    /**
     * Get uniqueID
     *
     * @return string
     */
    public function getUniqueID()
    {
        return $this->uniqueID;
    }

    /**
     * Add sources
     *
     * @param  \Oktolab\IntakeBundle\Entity\Source $source
     * @return File
     */
    public function addSource(\Oktolab\IntakeBundle\Entity\Source $source)
    {
        $this->sources[] = $source;

        return $this;
    }

    /**
     * Remove sources
     *
     * @param \Oktolab\IntakeBundle\Entity\Source $source
     */
    public function removeSource(\Oktolab\IntakeBundle\Entity\Source $source)
    {
        $this->sources->removeElement($source);
    }

    /**
     * Get sources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSources()
    {
        return $this->sources;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
        return $this;
    }

    public function getUploaderEmail()
    {
        return $this->uploaderEmail;
    }

    public function setUploaderEmail($uploaderEmail)
    {
        $this->uploaderEmail = $uploaderEmail;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }
}
