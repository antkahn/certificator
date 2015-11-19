<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadQuestionData extends DataFixtureLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/question.yml',
        );
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
