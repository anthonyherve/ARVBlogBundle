<?php

namespace ARV\BlogBundle\Manager;


use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class TagManager
{

    private $repository;
    private $em;

    public function __construct(TagRepository $repository, EntityManager $em)
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

    public function save(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    public function delete(Tag $tag)
    {
        $this->em->remove($tag);
        $this->em->flush();
    }

}
