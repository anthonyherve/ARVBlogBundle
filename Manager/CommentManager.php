<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;

class CommentManager
{

    private $repository;
    private $em;

    public function __construct(CommentRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getAll($article = null, $orderBy = true)
    {
        if ($article != null) {
            return $this->getByArticle($article, $orderBy);
        }
        if ($orderBy) {
            return $this->getRepository()->findBy(array(), array('dateModification' => 'DESC'));
        } else {
            return $this->getRepository()->findAll();
        }
    }

    public function count()
    {
        return $this->getRepository()->count();
    }

    public function getByArticle($article, $orderBy = true)
    {
        if ($orderBy) {
            return $this->getRepository()->findBy(array('article' => $article), array('dateModification' => 'DESC'));
        } else {
            return $this->getRepository()->findByArticle($article);
        }
    }

    public function save(Comment $comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function delete(Comment $comment)
    {
        $this->em->remove($comment);
        $this->em->flush();
    }

}
