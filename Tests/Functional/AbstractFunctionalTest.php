<?php

namespace ARV\BlogBundle\Tests\Functional;


use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class AbstractFunctionalTest
 * @package ARV\BlogBundle\Tests
 */
abstract class AbstractFunctionalTest extends WebTestCase
{

    /**
     * @var
     */
    protected $container;
    /**
     * @var
     */
    protected $client;

    /**
     *
     */
    public function setUp()
    {
        $this->loadFixtures(array(
            'ARV\BlogBundle\DataFixtures\ORM\LoadData'
        ));
        $this->container = $this->getContainer();
        $this->client = static::createClient();
    }

}
