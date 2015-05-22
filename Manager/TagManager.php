<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;

/**
 * Class TagManager
 * @package ARV\BlogBundle\Manager
 */
class TagManager
{

    /**
     * @var TagRepository
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
     * @param TagRepository $repository
     * @param EntityManager $em
     */
    public function __construct(TagRepository $repository, EntityManager $em, Paginator $paginator)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @return TagRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get all tags.
     * @param integer $page
     * @return array
     */
    public function getAll($page = 1)
    {
        return $this->paginator->paginate(
            $this->getRepository()->findAll(),
            $page,
            2
        );
    }

    /**
     * Count number of tags.
     * @return int
     */
    public function count()
    {
        return $this->getRepository()->count();
    }

    /**
     * Set tags.
     * From an array $tags, find those which are already in database to avoid to insert twice a tag.
     * @param $tags
     * @return array|ArrayCollection
     */
    public function setTags($tags)
    {
        $newTags = new ArrayCollection();
        foreach ($tags as $tag) {
            $tagFromDB = $this->getRepository()->findOneByName(strtolower($tag->getName()));
            if ($tagFromDB === null) {
                $newTags[] = $tag;
            } else {
                $newTags[] = $tagFromDB;
            }
        }
        return $newTags;
    }

    /**
     * Save / Update a $tag into database.
     * @param Tag $tag
     */
    public function save(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    /**
     * Delete a $tag from database.
     * @param Tag $tag
     */
    public function delete(Tag $tag)
    {
        $this->em->remove($tag);
        $this->em->flush();
    }

}
