<?php

namespace ARV\BlogBundle\Tests\Controller;


use ARV\BlogBundle\Tests\AbstractFunctionalTest;

/**
 * Class SearchControllerTest
 * @package ARV\BlogBundle\Tests\Controller
 */
class SearchControllerTest extends AbstractFunctionalTest
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
        $this->url = '/recherche';
    }

    /**
     *
     */
    public function testForm()
    {
        $client = static::createClient();
        $client->request('GET', $this->url . '/formulaire');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function testResult()
    {
        $client = static::createClient();
        $client->request('POST', $this->url . '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}

