<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizRepository")
 */
class Quiz
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Answer")
     * @ORM\JoinTable(name="quiz_answers",
     *      joinColumns={@ORM\JoinColumn(name="quiz_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="answer_id", referencedColumnName="id")}
     *      )
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="quizzes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct(User $user)
    {
        $this->answers = new ArrayCollection();
        $this->user = $user;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Answer $answer
     *
     * @return Quiz
     */
    public function addAnswer(Answer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * @param Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param User $user
     *
     * @return Quiz
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getGoodAnswers() {
        return array_filter($this->answers->toArray(), function($answer) {
            return $answer->isTrue();
        });
    }

    /**
     * @return int
     */
    public function getGoodAnswersCount() {
        return count($this->getGoodAnswers());
    }

    /**
     * @return array
     */
    public function getBadAnswers() {
        return array_filter($this->answers->toArray(), function($answer) {
            return !$answer->isTrue();
        });
    }

    /**
     * @return int
     */
    public function getBadAnswersCount() {
        return count($this->getBadAnswers());
    }
}
