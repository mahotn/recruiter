<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 */
class Skill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=JobDescription::class, mappedBy="skills")
     */
    private $jobDescriptions;

    public function __construct()
    {
        $this->jobDescriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|JobDescription[]
     */
    public function getJobDescriptions(): Collection
    {
        return $this->jobDescriptions;
    }

    public function addJobDescription(JobDescription $jobDescription): self
    {
        if (!$this->jobDescriptions->contains($jobDescription)) {
            $this->jobDescriptions[] = $jobDescription;
            $jobDescription->addSkill($this);
        }

        return $this;
    }

    public function removeJobDescription(JobDescription $jobDescription): self
    {
        if ($this->jobDescriptions->removeElement($jobDescription)) {
            $jobDescription->removeSkill($this);
        }

        return $this;
    }
}
