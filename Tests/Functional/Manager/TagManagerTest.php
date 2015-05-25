<?php

namespace ARV\BlogBundle\Tests\Functional\Manager;


use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Tests\Functional\AbstractFunctionalTest;

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
    public function test_tag_count()
    {
        $this->assertEquals(9, $this->manager->count());
    }

    /**
     *
     */
    public function test_tag_get_all()
    {
        $this->assertCount(9, $this->manager->getAll());
    }

    /**
     *
     */
    public function test_tag_set_tags()
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
    public function test_tag_save()
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
    public function test_tag_update()
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
    public function test_tag_delete()
    {
        $this->assertEquals(9, $this->manager->count());
        $tag = $this->manager->getRepository()->findOneByName("tag1");
        $this->manager->delete($tag);
        $this->assertEquals(8, $this->manager->count());
    }

}
