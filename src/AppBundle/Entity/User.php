<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sc_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Quiz", mappedBy="user")
     */
    private $quizzes;

    public function __construct()
    {
        parent::__construct();
        $this->quizzes = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Quiz $quiz
     *
     * @return User
     */
    public function addQuiz(Quiz $quiz)
    {
        $this->quizzes[] = $quiz;

        return $this;
    }

    /**
     * @param Quiz $quiz
     */
    public function removeQuiz(Quiz $quiz)
    {
        $this->quizzes->removeElement($quiz);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }
}
