<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;

/**
 * Class ArticleManager
 * @package ARV\BlogBundle\Manager
 */
class ArticleManager
{

    /**
     * @var ArticleRepository
     */
    private $repository;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param ArticleRepository $repository
     * @param EntityManager $em
     * @param Paginator $paginator
     */
    public function __construct(ArticleRepository $repository, EntityManager $em, Paginator $paginator)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @return ArticleRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get all articles.
     * @param integer $page
     * @return array
     */
    public function getAll($page = 1)
    {
        return $this->paginator->paginate(
            $this->getRepository()->findBy(array(), array('datePublication' => 'desc')),
            $page,
            10
        );
    }

    /**
     * Count number of articles.
     * @return int
     */
    public function count()
    {
        return $this->getRepository()->count();
    }

    /**
     * Search articles with $search.
     * @param string $search
     * @param boolean $publishedOnly
     * @param integer $page
     * @return array
     */
    public function search($search = '', $publishedOnly = false, $page = 1)
    {
        return $this->paginator->paginate(
            $this->getRepository()->search($search, $publishedOnly),
            $page,
            10
        );
    }

    /**
     * Search articles by tag.
     * @param $tag
     * @param integer $page
     * @param boolean $publishedOnly
     * @return mixed
     */
    public function searchByTag($tag, $page = 1, $publishedOnly = false)
    {
        return $this->paginator->paginate(
            $this->getRepository()->searchByTag($tag, $publishedOnly),
            $page,
            2
        );
    }

    /**
     * Save / Update an $article into database.
     * @param Article $article
     */
    public function save(Article $article)
    {
        $this->em->persist($article);
        $this->em->flush();
    }

    /**
     * Delete an $article from database.
     * @param Article $article
     */
    public function delete(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
    }

}
