<?php

namespace AppBundle\Entity;

use Bprs\AssetBundle\Entity\Asset as BaseAsset;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Table()
* @ORM\Entity()
 */
class Asset extends BaseAsset {

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="assets")
     */
    private $project;

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }
}
