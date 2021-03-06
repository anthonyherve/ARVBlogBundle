<?php

namespace ARV\BlogBundle\Tests\Functional\Controller;


use ARV\BlogBundle\Tests\Functional\AbstractFunctionalTest;

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
    public function test_blog_index()
    {
        $this->client->request('GET', $this->url);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}

