<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultsRepository")
 */
class Results
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Criteria", inversedBy="results")
     */
    private $criteria;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $oral;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $elearning;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Students", inversedBy="results")
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    public function setCriteria(?Criteria $criteria): self
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getOral(): ?bool
    {
        return $this->oral;
    }

    public function setOral(?bool $oral): self
    {
        $this->oral = $oral;

        return $this;
    }

    public function getElearning(): ?string
    {
        return $this->elearning;
    }

    public function setElearning(?string $elearning): self
    {
        $this->elearning = $elearning;

        return $this;
    }

    public function getStudent(): ?Students
    {
        return $this->student;
    }

    public function setStudent(?Students $student): self
    {
        $this->student = $student;

        return $this;
    }
}
