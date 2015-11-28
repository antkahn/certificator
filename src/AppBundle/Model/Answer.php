<?php

namespace AppBundle\Model;

class Answer
{
    /**
     * @var string
     */
    private $statement;

    /**
     * @var integer
     */
    private $id;

    public function __construct(\AppBundle\Entity\Answer $answer)
    {
        $this->statement = $answer->getStatement();
        $this->id = $answer->getId();
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
