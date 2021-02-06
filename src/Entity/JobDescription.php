<?php

namespace App\Entity;

use App\Repository\JobDescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobDescriptionRepository::class)
 */
class JobDescription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $jobTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Mission::class, inversedBy="jobDescriptions", fetch="EAGER", cascade={"persist", "remove"})
     */
    private $missions;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, inversedBy="jobDescriptions",  cascade={"persist", "remove"})
     */
    private $skills;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="jobDescription")
     */
    private $projects;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobDescriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        $this->missions->removeElement($mission);

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setJobDescription($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getJobDescription() === $this) {
                $project->setJobDescription(null);
            }
        }

        return $this;
    }

    public function getOwnerId(): ?User
    {
        return $this->owner;
    }

    public function setOwnerId(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
