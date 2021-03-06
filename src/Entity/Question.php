<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity=QuestionGroup::class, inversedBy="questions")
     */
    private $questionGroup;

    /**
     * @ORM\OneToOne(targetEntity=Question::class, cascade={"persist", "remove"})
     */
    private $next;

    /**
     * @ORM\OneToOne(targetEntity=Question::class, cascade={"persist", "remove"})
     */
    private $previos;

    /**
     * @ORM\ManyToOne(targetEntity=Scenario::class, inversedBy="questions")
     */
    private $scenario;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMultipleChoice;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $currectAnswer;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $answerDescription;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getQuestionGroup(): ?QuestionGroup
    {
        return $this->questionGroup;
    }

    public function setQuestionGroup(?QuestionGroup $questionGroup): self
    {
        $this->questionGroup = $questionGroup;

        return $this;
    }

    public function __toString()
    {
        return 'id='.$this->id. ', title='.$this->title.', description='.$this->description
            .', points='.$this->points;
    }

    public function getNext(): ?self
    {
        return $this->next;
    }

    public function setNext(?self $next): self
    {
        $this->next = $next;

        return $this;
    }

    public function getPrevios(): ?self
    {
        return $this->previos;
    }

    public function setPrevios(?self $previos): self
    {
        $this->previos = $previos;

        return $this;
    }

    public function getScenario(): ?Scenario
    {
        return $this->scenario;
    }

    public function setScenario(?Scenario $scenario): self
    {
        $this->scenario = $scenario;

        return $this;
    }

    public function getIsMultipleChoice(): ?bool
    {
        return $this->isMultipleChoice;
    }

    public function setIsMultipleChoice(?bool $isMultipleChoice): self
    {
        $this->isMultipleChoice = $isMultipleChoice;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getCurrectAnswer(): ?string
    {
        return $this->currectAnswer;
    }

    public function setCurrectAnswer(?string $currectAnswer): self
    {
        $this->currectAnswer = $currectAnswer;

        return $this;
    }

    public function getAnswerDescription(): ?string
    {
        return $this->answerDescription;
    }

    public function setAnswerDescription(?string $answerDescription): self
    {
        $this->answerDescription = $answerDescription;

        return $this;
    }

}
