<?php

namespace AppBundle\Model;

class Question
{
    /**
     * @var Answer[]
     */
    private $answers;

    /**
     * @var string
     */
    private $statement;

    public function __construct(\AppBundle\Entity\Question $question, $answers)
    {
        $this->statement = $question->getStatement();
        $this->answers = $answers;
    }

    /**
     * @param Answer[] $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param $statement
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }
}
