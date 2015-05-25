<?php

namespace ARV\BlogBundle\Tests\Functional\Manager;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Tests\Functional\AbstractFunctionalTest;

/**
 * Class ArticleManagerTest
 * @package ARV\BlogBundle\Tests\Manager
 */
class ArticleManagerTest extends AbstractFunctionalTest
{

    /**
     * @var
     */
    private $manager;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('arv_blog_manager_article');
    }

    /**
     *
     */
    public function test_article_count()
    {
        $this->assertEquals(3, $this->manager->count());
    }

    /**
     *
     */
    public function test_article_get_all()
    {
        $this->assertCount(3, $this->manager->getAll());
    }

    /**
     *
     */
    public function test_article_search_empty()
    {
        $articles = $this->manager->search();
        $this->assertCount(3, $articles);
    }

    /**
     *
     */
    public function test_article_searc_with_results()
    {
        $articles = $this->manager->search("Presents");
        $this->assertCount(1, $articles);
    }

    /**
     *
     */
    public function test_article_search_with_no_result()
    {
        $articles = $this->manager->search("Bla bla bla");
        $this->assertCount(0, $articles);
    }

    /**
     *
     */
    public function test_article_search_empty_published()
    {
        $articles = $this->manager->search("", true);
        $this->assertCount(2, $articles);
    }

    /**
     *
     */
    public function test_article_save()
    {
        $this->assertEquals(3, $this->manager->count());
        $article = new Article();
        $article->setTitle("New article");
        $article->setContent("Content of new article");
        $this->manager->save($article);
        $this->assertEquals(4, $this->manager->count());
    }

    /**
     *
     */
    public function test_article_update()
    {
        $this->assertEquals(3, $this->manager->count());
        $article = $this->manager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $article->setTitle("New title");
        $this->manager->save($article);
        $this->assertEquals(3, $this->manager->count());
    }

    /**
     *
     */
    public function test_article_delete()
    {
        $this->assertEquals(3, $this->manager->count());
        $article = $this->manager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $this->manager->delete($article);
        $this->assertEquals(2, $this->manager->count());
    }

}
