<?php

namespace ARV\BlogBundle\Tests\Functional\Controller;


use ARV\BlogBundle\Tests\Functional\AbstractFunctionalTest;

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
    public function test_search_form()
    {
        $client = static::createClient();
        $client->request('GET', $this->url . '/formulaire');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_search_result()
    {
        $client = static::createClient();
        $client->request('POST', $this->url . '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}

