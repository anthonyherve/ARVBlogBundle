<?php

namespace ARV\BlogBundle\Tests\Controller;


use ARV\BlogBundle\Tests\AbstractFunctionalTest;

/**
 * Class BlogControllerTest
 * @package ARV\BlogBundle\Tests\Controller
 */
class BlogControllerTest extends AbstractFunctionalTest
{

    /**
     * @var
     */
    private $url;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->url = '/';
    }

    /**
     *
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', $this->url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}

