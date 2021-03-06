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
     * @ORM\Column(type="integer", nullable=true)
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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coeforal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefelearning;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $average;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acquis;

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

    public function getOral(): ?int
    {
        return $this->oral;
    }

    public function setOral(?bool $oral): self
    {
        $this->oral = $oral;

        return $this;
    }

    public function getElearning(): ?int
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

    public function getCoeforal(): ?float
    {
        return $this->coeforal;
    }

    public function setCoeforal(?float $coeforal): self
    {
        $this->coeforal = $coeforal;

        return $this;
    }

    public function getCoefelearning(): ?float
    {
        return $this->coefelearning;
    }

    public function setCoefelearning(?float $coefelearning): self
    {
        $this->coefelearning = $coefelearning;

        return $this;
    }

    public function getAverage(): ?float
    {
        return $this->average;
    }

    public function setAverage(?float $average): self
    {
        $this->average = $average;

        return $this;
    }

    public function getAcquis(): ?string
    {
        return $this->acquis;
    }

    public function setAcquis(?string $acquis): self
    {
        $this->acquis = $acquis;

        return $this;
    }
}
