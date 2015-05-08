<?php

namespace ARV\BlogBundle\Tests\Entity;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Entity\Tag;

/**
 * Class TagTest
 * @package ARV\BlogBundle\Tests\Entity
 */
class TagTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Manipulate a tag.
     */
    public function testManipulateTag()
    {
        $tag = new Tag();
        $tag->addArticle(new Article());
        $tag->addArticle(new Article());
        $this->assertEquals(2, count($tag->getArticles()));
        $articles = array(new Article());
        $tag->setArticles($articles);
        $this->assertEquals(1, count($tag->getArticles()));
    }

}
