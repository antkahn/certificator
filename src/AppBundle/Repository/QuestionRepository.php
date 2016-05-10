<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Question;
use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    /**
     * @return Question[]
     */
    public function getRandomQuestions()
    {
        return  $this->createQueryBuilder('q')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $count
     * @param Category $category
     *
     * @return Question[]
     */
    public function getRandomQuestionsByCategory(Category $category, $count = 10)
    {
        return  $this->createQueryBuilder('q')
            ->andWhere('q.category = :category')
            ->setParameter('category', $category)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
}
