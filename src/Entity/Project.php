<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $contract;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $teamSize;

    /**
     * @ORM\ManyToOne(targetEntity=JobDescription::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobDescription;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="applications")
     */
    private $candidates;

    /**
     * @ORM\ManyToOne(targetEntity=Questionnaire::class, inversedBy="projects")
     */
    private $questionnaire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $globalExperienceLevel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $experienceLevelAtPosition;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $educationLevel;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $managerJobTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $uniqueLink;

    private $BASE_URL = "https://127.0.0.1/recrutement/";

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContract(): ?string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getTeamSize(): ?string
    {
        return $this->teamSize;
    }

    public function setTeamSize(?string $teamSize): self
    {
        $this->teamSize = $teamSize;

        return $this;
    }

    public function getJobDescription(): ?JobDescription
    {
        return $this->jobDescription;
    }

    public function setJobDescription(?JobDescription $jobDescription): self
    {
        $this->jobDescription = $jobDescription;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(User $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
            $candidate->addApplication($this);
        }

        return $this;
    }

    public function removeCandidate(User $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            $candidate->removeApplication($this);
        }

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    public function getGlobalExperienceLevel(): ?int
    {
        return $this->globalExperienceLevel;
    }

    public function setGlobalExperienceLevel(?int $globalExperienceLevel): self
    {
        $this->globalExperienceLevel = $globalExperienceLevel;

        return $this;
    }

    public function getExperienceLevelAtPosition(): ?int
    {
        return $this->experienceLevelAtPosition;
    }

    public function setExperienceLevelAtPosition(?int $experienceLevelAtPosition): self
    {
        $this->experienceLevelAtPosition = $experienceLevelAtPosition;

        return $this;
    }

    public function getEducationLevel(): ?string
    {
        return $this->educationLevel;
    }

    public function setEducationLevel(?string $educationLevel): self
    {
        $this->educationLevel = $educationLevel;

        return $this;
    }

    public function getManagerJobTitle(): ?string
    {
        return $this->managerJobTitle;
    }

    public function setManagerJobTitle(?string $managerJobTitle): self
    {
        $this->managerJobTitle = $managerJobTitle;

        return $this;
    }

    public function getUniqueLink(): ?string
    {
        return $this->uniqueLink;
    }

    public function setUniqueLink(?string $uniqueLink): self
    {
        $this->uniqueLink = $uniqueLink;

        return $this;
    }

    /**
     * Fonction qui génère une lien d'identification unique pour le projet d'un client.
     * Construit une chaîne de caractère unique et l'ajoute au nom de domaine du site web afin de générer une URL fonctionnelle.
     * @param string $projectName
     * @return string
     */
    public function generateUniqueLink(string $projectName): string {
        // Retourne le timestamp UNIX actuel en microsecondes et le convertit en chaîne de caractères.
        $timestamp = strval(round(microtime(true) * 1000));
        // Génère un nombre aléatoire compris entre 1 et 9999 et la convertit en chaîne de caractères.
        $randomNb = strval(rand(1, 9999));

        // Création de l'URL.
        $string = $this->BASE_URL;
        $string .= $projectName . "_";
        $string .= $timestamp . "_";
        $string .= $randomNb;

        return $string;
    }
}
