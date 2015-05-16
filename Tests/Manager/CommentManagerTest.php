<?php

namespace ARV\BlogBundle\Tests\Manager;


use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Tests\AbstractFunctionalTest;

/**
 * Class CommentManagerTest
 * @package ARV\BlogBundle\Tests\Manager
 */
class CommentManagerTest extends AbstractFunctionalTest
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
        $this->manager = $this->container->get('arv_blog_manager_comment');
        $this->articleManager = $this->container->get('arv_blog_manager_article');
    }

    /**
     *
     */
    public function testCount()
    {
        $this->assertEquals(3, $this->manager->count());
    }

    /**
     *
     */
    public function testGetAllOrderedWithNoArticle()
    {
        $this->assertCount(3, $this->manager->getAll());
    }

    /**
     *
     */
    public function testGetAllNotOrderedWithNoArticle()
    {
        $this->assertCount(3, $this->manager->getAll(null, false));
    }

    /**
     *
     */
    public function testGetAllOrderedWithArticle()
    {
        $article = $this->articleManager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $this->assertCount(1, $this->manager->getAll($article));
    }

    /**
     *
     */
    public function testGetAllNotOrderedWithArticle()
    {
        $article = $this->articleManager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $this->assertCount(1, $this->manager->getAll($article, false));
    }

    /**
     *
     */
    public function testExistByDateAndIpZero()
    {
        $this->assertEquals(false, $this->manager->existByDateAndIp(0, '192.168.0.10'));
    }

    /**
     *
     */
    public function testGetByDateAndIp()
    {
        $this->assertEquals(true, $this->manager->existByDateAndIp(5, '192.168.0.10'));
    }

    /**
     *
     */
    public function testSave()
    {
        $this->assertEquals(3, $this->manager->count());
        $article = $this->articleManager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $comment = new Comment();
        $comment->setEmail("user@gmail.com");
        $comment->setIp("192.168.0.10");
        $comment->setContent("Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci,
            sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis.");
        $comment->setArticle($article);
        $this->manager->save($comment);
        $this->assertEquals(4, $this->manager->count());
    }

    /**
     *
     */
    public function testUpdate()
    {
        $this->assertEquals(3, $this->manager->count());
        $comment = $this->manager->getRepository()->findOneByEmail("user@gmail.com");
        $comment->setEmail("user1@gmail.com");
        $this->manager->save($comment);
        $this->assertEquals(3, $this->manager->count());
    }

    /**
     *
     */
    public function testDelete()
    {
        $this->assertEquals(3, $this->manager->count());
        $comment = $this->manager->getRepository()->findOneByEmail("user@gmail.com");
        $this->manager->delete($comment);
        $this->assertEquals(2, $this->manager->count());
    }

}
