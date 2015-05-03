<?php

namespace ARV\BlogBundle\Tests\Manager;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Tests\AbstractFunctionalTest;

class ArticleManagerTest extends AbstractFunctionalTest
{

    private $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('arv_blog_manager_article');
    }

    public function testCount()
    {
        $this->assertEquals(3, $this->manager->count());
    }

    public function testGetAll()
    {
        $this->assertCount(3, $this->manager->getAll());
    }

    public function testSearchEmpty() {
        $articles = $this->manager->search();
        $this->assertCount(3, $articles);
    }

    public function testSearchWithResults() {
        $articles = $this->manager->search("Presents");
        $this->assertCount(1, $articles);
    }

    public function testSearchWithNoResult() {
        $articles = $this->manager->search("Bla bla bla");
        $this->assertCount(0, $articles);
    }

    public function testSave() {
        $this->assertEquals(3, $this->manager->count());
        $article = new Article();
        $article->setTitle("New article");
        $article->setContent("Content of new article");
        $this->manager->save($article);
        $this->assertEquals(4, $this->manager->count());
    }

    public function testUpdate() {
        $this->assertEquals(3, $this->manager->count());
        $article = $this->manager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $article->setTitle("New title");
        $this->manager->save($article);
        $this->assertEquals(3, $this->manager->count());
    }

    public function testDelete() {
        $this->assertEquals(3, $this->manager->count());
        $article = $this->manager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $this->manager->delete($article);
        $this->assertEquals(2, $this->manager->count());
    }

}