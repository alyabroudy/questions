<?php

namespace App\Entity;

use App\Repository\QuestionGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionGroupRepository::class)
 */
class QuestionGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="questionGroup")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Scenario::class, mappedBy="questionGroup")
     */
    private $scenarios;

    public function __construct($name =null)
    {
        $this->name = $name;
        $this->questions = new ArrayCollection();
        $this->scenarios = new ArrayCollection();
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
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuestionGroup($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuestionGroup() === $this) {
                $question->setQuestionGroup(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return 'id='.$this->id. ', name='.$this->name.', description='.$this->description;
    }

    /**
     * @return Collection|Scenario[]
     */
    public function getScenarios(): Collection
    {
        return $this->scenarios;
    }

    public function addScenario(Scenario $scenario): self
    {
        if (!$this->scenarios->contains($scenario)) {
            $this->scenarios[] = $scenario;
            $scenario->setQuestionGroup($this);
        }

        return $this;
    }

    public function removeScenario(Scenario $scenario): self
    {
        if ($this->scenarios->contains($scenario)) {
            $this->scenarios->removeElement($scenario);
            // set the owning side to null (unless already changed)
            if ($scenario->getQuestionGroup() === $this) {
                $scenario->setQuestionGroup(null);
            }
        }

        return $this;
    }


}
