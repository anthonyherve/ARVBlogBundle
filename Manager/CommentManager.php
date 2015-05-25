<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;

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
     * @var Paginator
     */
    private $paginator;

    /**
     * @param CommentRepository $repository
     * @param EntityManager $em
     */
    public function __construct(CommentRepository $repository, EntityManager $em, Paginator $paginator)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->paginator = $paginator;
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
     * @param integer $page
     * @param null $article
     * @param bool $orderBy
     * @return array
     */
    public function getAll($page = 1, $article = null, $orderBy = true)
    {
        if ($article !== null) {
            $comments = $this->getByArticle($article, $orderBy);
        } else {
            if ($orderBy) {
                $comments = $this->getRepository()->findBy(array(), array('dateModification' => 'DESC'));
            } else {
                $comments = $this->getRepository()->findAll();
            }
        }

        return $this->paginator->paginate(
            $comments,
            $page,
            10
        );
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
