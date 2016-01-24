<?php

namespace AppBundle\Model;

class QuizConfiguration
{
    /**
     * @var \AppBundle\Entity\Category
     */
    private $category;

    /**
     * @var integer
     */
    private $questionAmount;

    /**
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \AppBundle\Entity\Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getQuestionAmount()
    {
        return $this->questionAmount;
    }
    /**
     * @param int $questionAmount
     */
    public function setQuestionAmount($questionAmount)
    {
        $this->questionAmount = $questionAmount;
    }
}
