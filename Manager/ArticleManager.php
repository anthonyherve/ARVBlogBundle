<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;

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
     * @param ArticleRepository $repository
     * @param EntityManager $em
     */
    public function __construct(ArticleRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
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
     * @return array
     */
    public function getAll()
    {
        return $this->getRepository()->findAll();
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
     * @return array
     */
    public function search($search = '')
    {
        return $this->getRepository()->search($search);
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
