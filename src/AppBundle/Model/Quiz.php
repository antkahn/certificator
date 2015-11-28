<?php

namespace AppBundle\Model;

class Quiz
{
    /**
     * @var Question[]
     */
    private $questions;

    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    /**
     * @param Question[] $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return Question[]
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers()
    {
        $answers = [];

        foreach ($this->questions as $question) {
            $answers[] = $question->getAnswers();
        }

        return $answers;
    }
}
