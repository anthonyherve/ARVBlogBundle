<?php

namespace ARV\BlogBundle\Tests\Controller;


use ARV\BlogBundle\Tests\AbstractFunctionalTest;

/**
 * Class TagControllerTest
 * @package ARV\BlogBundle\Tests\Controller
 */
class TagControllerTest extends AbstractFunctionalTest
{

    /**
     * @var
     */
    private $manager;
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
        $this->manager = $this->container->get('arv_blog_manager_tag');
        $this->url = '/tag';
    }

    /**
     *
     */
    public function testManage()
    {
        $this->client->request('GET', $this->url . '/admin');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function testShow()
    {
        $tag = $this->manager->getRepository()->findOneByName('tag3');
        $this->client->request('GET', $this->url . '/' . $tag->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function testCreateOK()
    {
        $count = $this->countTags();
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_tag_submit')->form(array(
            'arv_blogbundle_tag[name]' => 'tag11'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count + 1, $this->countTags());
    }

    /**
     *
     */
    public function testCreateKO()
    {
        $count = $this->countTags();
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_tag_submit')->form(array(
            'arv_blogbundle_tag[_token]' => ''
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countTags());
    }

    /**
     *
     */
    public function testEditOK()
    {
        $count = $this->countTags();
        $tag = $this->manager->getRepository()->findOneByName('tag5');
        $crawler = $this->client->request('GET', $this->url . '/' . $tag->getId() . '/modifier');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_tag_submit')->form(array(
            'arv_blogbundle_tag[name]' => 'tag12'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countTags());
    }

    /**
     *
     */
    public function testEditKO()
    {
        $count = $this->countTags();
        $tag = $this->manager->getRepository()->findOneByName('tag5');
        $crawler = $this->client->request('GET', $this->url . '/' . $tag->getId() . '/modifier');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_tag_submit')->form(array(
            'arv_blogbundle_tag[_token]' => ''
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countTags());
    }

    /**
     *
     */
    public function testDeleteOK()
    {
        $count = $this->countTags();
        $tag = $this->manager->getRepository()->findOneByName('tag3');
        $crawler = $this->client->request('GET', $this->url . '/' . $tag->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('form_submit')->form(array());
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count - 1, $this->countTags());
    }

    /**
     *
     */
    public function testDeleteKO()
    {
        $count = $this->countTags();
        $tag = $this->manager->getRepository()->findOneByName('tag3');
        $crawler = $this->client->request('GET', $this->url . '/' . $tag->getId());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('form_submit')->form(array(
            'form[_token]' => ''
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countTags());
    }

    /**
     * @return mixed
     */
    private function countTags()
    {
        return $this->manager->count();
    }

}

