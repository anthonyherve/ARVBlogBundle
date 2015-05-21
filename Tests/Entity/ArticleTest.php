<?php

namespace ARV\BlogBundle\Tests\Entity;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Entity\Comment;

/**
 * Class ArticleTest
 * @package ARV\BlogBundle\Tests\Entity
 */
class ArticleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Manipulate an article.
     */
    public function test_manipulate_article()
    {
        $article = new Article();
        $article->setSlug('new-slug');
        $this->assertEquals('new-slug', $article->getSlug());
        $article->addComment(new Comment());
        $article->addComment(new Comment());
        $this->assertEquals(2, count($article->getComments()));
        $comments = array(new Comment());
        $article->setComments($comments);
        $this->assertEquals(1, count($article->getComments()));
    }

}
