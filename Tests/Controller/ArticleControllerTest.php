<?php

namespace ARV\BlogBundle\Tests\Controller;


use ARV\BlogBundle\Tests\AbstractFunctionalTest;

/**
 * Class ArticleControllerTest
 * @package ARV\BlogBundle\Tests\Controller
 */
class ArticleControllerTest extends AbstractFunctionalTest
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
        $this->manager = $this->container->get('arv_blog_manager_article');
        $this->url = '/article';
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
    public function testList()
    {
        $this->client->request('GET', $this->url . '/liste');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function testSearch()
    {
        $this->client->request('GET', $this->url . '/liste-recherche');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function testShow()
    {
        $article = $this->manager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $this->client->request('GET', $this->url . '/' . $article->getId() . '-' . $article->getSlug());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function testCreateOK()
    {
        $count = $this->countArticles();
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_article_submit')->form(array(
            'arv_blogbundle_article[title]' => 'Edit title',
            'arv_blogbundle_article[content]' => 'Edit content'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count + 1, $this->countArticles());
    }

    /**
     *
     */
    public function testCreateKO()
    {
        $count = $this->countArticles();
        $crawler = $this->client->request('GET', $this->url . '/nouveau');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('arv_blogbundle_article_submit')->form(array(
            'arv_blogbundle_article[_token]' => ''
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countArticles());
    }

    /**
     *
     */
    public function testEditOK()
    {
        $count = $this->countArticles();
        $article = $this->manager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $crawler = $this->client->request(
            'GET',
            $this->url . '/' . $article->getId() . '-' . $article->getSlug() . '/modifier'
        );
        $form = $crawler->selectButton('arv_blogbundle_article_submit')->form(array(
            'arv_blogbundle_article[title]' => 'Edit title',
            'arv_blogbundle_article[content]' => 'Edit content'
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countArticles());
    }

    /**
     *
     */
    public function testEditKO()
    {
        $count = $this->countArticles();
        $article = $this->manager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $crawler = $this->client->request(
            'GET',
            $this->url . '/' . $article->getId() . '-' . $article->getSlug() . '/modifier'
        );
        $form = $crawler->selectButton('arv_blogbundle_article_submit')->form(array(
            'arv_blogbundle_article[_token]' => ''
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countArticles());
    }

    /**
     *
     */
    public function testDelete()
    {
        $count = $this->countArticles();
        $article = $this->manager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $crawler = $this->client->request('GET', $this->url . '/' . $article->getId() . '-' . $article->getSlug());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('form_submit')->form(array());
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count - 1, $this->countArticles());
    }

    /**
     * @return mixed
     */
    private function countArticles()
    {
        return $this->manager->count();
    }

}
