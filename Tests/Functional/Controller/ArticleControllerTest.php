<?php

namespace ARV\BlogBundle\Tests\Functional\Controller;


use ARV\BlogBundle\Tests\Functional\AbstractFunctionalTest;

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
    public function test_article_manage()
    {
        $this->client->request('GET', $this->url . '/admin');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_article_list()
    {
        $this->client->request('GET', $this->url . '/liste');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_article_search()
    {
        $this->client->request('GET', $this->url . '/liste-recherche');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_article_show()
    {
        $article = $this->manager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $this->client->request('GET', $this->url . '/' . $article->getId() . '-' . $article->getSlug());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    public function test_article_create_ok()
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
    public function test_article_create_ko()
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
    public function test_article_edit_ok()
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
    public function test_article_edit_ko()
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
    public function test_article_delete_ok()
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
     *
     */
    public function test_article_delete_ko()
    {
        $count = $this->countArticles();
        $article = $this->manager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $crawler = $this->client->request('GET', $this->url . '/' . $article->getId() . '-' . $article->getSlug());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $form = $crawler->selectButton('form_submit')->form(array(
            'form[_token]' => ''
        ));
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertEquals($count, $this->countArticles());
    }

    /**
     * @return mixed
     */
    private function countArticles()
    {
        return $this->manager->count();
    }

}
