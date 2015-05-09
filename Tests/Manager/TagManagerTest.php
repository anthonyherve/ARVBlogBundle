<?php

namespace ARV\BlogBundle\Tests\Manager;


use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Tests\AbstractFunctionalTest;

/**
 * Class TagManagerTest
 * @package ARV\BlogBundle\Tests\Manager
 */
class TagManagerTest extends AbstractFunctionalTest
{

    /**
     * @var
     */
    private $manager;
    /**
     * @var
     */
    private $articleManager;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('arv_blog_manager_tag');
        $this->articleManager = $this->container->get('arv_blog_manager_article');
    }

    /**
     *
     */
    public function testCount()
    {
        $this->assertEquals(9, $this->manager->count());
    }

    /**
     *
     */
    public function testGetAll()
    {
        $this->assertCount(9, $this->manager->getAll());
    }

    /**
     *
     */
    public function testSetTags()
    {
        $count = $this->manager->count();
        $article = $this->articleManager->getRepository()->findOneByTitle('HTML Ipsum Presents');
        $tags = array(new Tag('tag1'), new Tag('tag20'));
        $article->setTags($this->manager->setTags($tags));
        $this->articleManager->save($article);
        $this->assertEquals($count + 1, $this->manager->count());
    }

    /**
     *
     */
    public function testSave()
    {
        $this->assertEquals(9, $this->manager->count());
        $article = $this->articleManager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $tag = new Tag('tag10');
        $tag->addArticle($article);
        $this->manager->save($tag);
        $this->assertEquals(10, $this->manager->count());
    }

    /**
     *
     */
    public function testUpdate()
    {
        $this->assertEquals(9, $this->manager->count());
        $tag = $this->manager->getRepository()->findOneByName("tag1");
        $tag->setName('new_tag');
        $this->manager->save($tag);
        $this->assertEquals(9, $this->manager->count());
    }

    /**
     *
     */
    public function testDelete()
    {
        $this->assertEquals(9, $this->manager->count());
        $tag = $this->manager->getRepository()->findOneByName("tag1");
        $this->manager->delete($tag);
        $this->assertEquals(8, $this->manager->count());
    }

}
