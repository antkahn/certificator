<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Question;
use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    /**
     * @param int $count
     *
     * @return Question[]
     */
    public function getRandomQuestions($category, $count = 10)
    {
        return  $this->createQueryBuilder('q')
            ->andWhere('q.category = :category')
            ->setParameter('category', $category)
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
}
