<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;

class ArticleManager
{

    private $repository;
    private $em;

    public function __construct(ArticleRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    public function count()
    {
        return $this->getRepository()->count();
    }

    public function search($search = '')
    {
        return $this->getRepository()->search($search);
    }

    public function save(Article $article)
    {
        $this->em->persist($article);
        $this->em->flush();
    }

    public function delete(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
    }

}
