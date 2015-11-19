<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadCategoryData extends DataFixtureLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/category.yml',
        );
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
