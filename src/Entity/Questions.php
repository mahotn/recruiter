<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $options = [];

    /**
     * @ORM\ManyToMany(targetEntity=Questionnaire::class, inversedBy="questions")
     */
    private $questionnaireIds;

    public function __construct()
    {
        $this->questionnaireIds = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return Collection|Questionnaire[]
     */
    public function getQuestionnaireIds(): Collection
    {
        return $this->questionnaireIds;
    }

    public function addQuestionnaireId(Questionnaire $questionnaireId): self
    {
        if (!$this->questionnaireIds->contains($questionnaireId)) {
            $this->questionnaireIds[] = $questionnaireId;
        }

        return $this;
    }

    public function removeQuestionnaireId(Questionnaire $questionnaireId): self
    {
        $this->questionnaireIds->removeElement($questionnaireId);

        return $this;
    }
}
