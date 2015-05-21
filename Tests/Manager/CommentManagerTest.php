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
    public function test_comment_count()
    {
        $this->assertEquals(3, $this->manager->count());
    }

    /**
     *
     */
    public function test_comment_get_all_ordered_with_no_article()
    {
        $this->assertCount(3, $this->manager->getAll());
    }

    /**
     *
     */
    public function test_comment_get_all_not_ordered_with_no_article()
    {
        $this->assertCount(3, $this->manager->getAll(null, false));
    }

    /**
     *
     */
    public function test_comment_get_all_ordered_with_article()
    {
        $article = $this->articleManager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $this->assertCount(1, $this->manager->getAll($article));
    }

    /**
     *
     */
    public function test_comment_get_all_not_ordered_with_article()
    {
        $article = $this->articleManager->getRepository()->findOneByTitle("HTML Ipsum Presents");
        $this->assertCount(1, $this->manager->getAll($article, false));
    }

    /**
     *
     */
    public function test_comment_exist_by_date_and_ip_zero()
    {
        $this->assertEquals(false, $this->manager->existByDateAndIp(0, '192.168.0.10'));
    }

    /**
     *
     */
    public function test_comment_get_by_date_and_ip()
    {
        $this->assertEquals(true, $this->manager->existByDateAndIp(5, '192.168.0.10'));
    }

    /**
     *
     */
    public function test_comment_save()
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
    public function test_comment_update()
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
    public function test_comment_delete()
    {
        $this->assertEquals(3, $this->manager->count());
        $comment = $this->manager->getRepository()->findOneByEmail("user@gmail.com");
        $this->manager->delete($comment);
        $this->assertEquals(2, $this->manager->count());
    }

}
