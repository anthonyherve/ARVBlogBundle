<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class CommentManager
 * @package ARV\BlogBundle\Manager
 */
class CommentManager
{

    /**
     * @var CommentRepository
     */
    private $repository;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param CommentRepository $repository
     * @param EntityManager $em
     */
    public function __construct(CommentRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @return CommentRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get all comments from an $article $oderBy last modification date.
     * @param null $article
     * @param bool $orderBy
     * @return array
     */
    public function getAll($article = null, $orderBy = true)
    {
        if ($article !== null) {
            return $this->getByArticle($article, $orderBy);
        }
        if ($orderBy) {
            return $this->getRepository()->findBy(array(), array('dateModification' => 'DESC'));
        } else {
            return $this->getRepository()->findAll();
        }
    }

    /**
     * Count number of comments.
     * @return int
     */
    public function count()
    {
        return $this->getRepository()->count();
    }

    /**
     * Get comments for an $article $orderBy last modification date.
     * @param $article
     * @param bool $orderBy
     * @return array
     */
    public function getByArticle($article, $orderBy = true)
    {
        if ($orderBy) {
            return $this->getRepository()->findBy(array('article' => $article), array('dateModification' => 'DESC'));
        } else {
            return $this->getRepository()->findBy(array('article' => $article));
        }
    }

    /**
     * Return true if a comment with $ip exists and was written in last $minutes.
     * @param $minutes
     * @param $ip
     * @return bool
     */
    public function existByDateAndIp($minutes, $ip)
    {
        $comments = $this->getRepository()->findBy(array('ip' => $ip), array('dateCreation' => 'DESC'));
        foreach($comments as $comment) {
            $now = new \DateTime();
            $diff = abs($now->getTimestamp() - $comment->getDateCreation()->getTimestamp());
            if ($diff < $minutes * 60) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save / Update a $comment into database.
     * @param Comment $comment
     */
    public function save(Comment $comment)
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * Delete a $comment from database.
     * @param Comment $comment
     */
    public function delete(Comment $comment)
    {
        $this->em->remove($comment);
        $this->em->flush();
    }

}
