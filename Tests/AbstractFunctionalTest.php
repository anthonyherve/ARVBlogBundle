<?php

namespace ARV\BlogBundle\Tests;


use Liip\FunctionalTestBundle\Test\WebTestCase;

abstract class AbstractFunctionalTest extends WebTestCase {

    protected $container;

    public function setUp()
    {
        $this->loadFixtures(array(
            'ARV\BlogBundle\DataFixtures\ORM\LoadData'
        ));
        $this->container = $this->getContainer();
    }

}