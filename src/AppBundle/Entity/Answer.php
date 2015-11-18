<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
class Answer
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
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $statement;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $veracity;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;
    

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $statement
     *
     * @return Answer
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param boolean $veracity
     *
     * @return Answer
     */
    public function setVeracity($veracity)
    {
        $this->veracity = $veracity;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getVeracity()
    {
        return $this->veracity;
    }

    /**
     * @param Question $question
     *
     * @return Answer
     */
    public function setQuestion(Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
