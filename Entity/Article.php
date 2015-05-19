<?php

namespace ARV\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 * @package ARV\BlogBundle\Entity
 *
 * @ORM\Table(name = "blog_article")
 * @ORM\Entity(repositoryClass="ARV\BlogBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */

class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime")
     */
    private $dateModification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publication", type="datetime")
     */
    private $datePublication;

    /**
     * @var tags
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinTable(name="blog_article_tag")
     */
    private $tags;

    /**
     * @var comments
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article", cascade={"remove"})
     **/
    private $comments;

    /**
     * @var
     * Link with user defined dynamically by UserRelationSubscriber.
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->datePublication = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setDateModification(new \DateTime('now'));
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if ($this->dateCreation === null) {
            $this->setDateCreation(new \DateTime('now'));
            $this->setDateModification(new \DateTime('now'));
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Actuality
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Actuality
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * @param \DateTime $datePublication
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;
    }

    /**
     * Get tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param ArrayCollection $tags
     * @return Article
     */
    public function setTags($tags)
    {
        foreach ($tags as $tag) {
            $tag->addArticle($this);
        }
        $this->tags = $tags;

        return $this;
    }

    /**
     * Add a tag to collection.
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $tag->addArticle($this);
        $this->tags[] = $tag;
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments
     *
     * @param ArrayCollection $comments
     * @return Article
     */
    public function setComments($comments)
    {
        foreach ($comments as $comment) {
            $comment->setArticle($this);
        }
        $this->comments = $comments;

        return $this;
    }

    /**
     * Add a tag to collection.
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $comment->setArticle($this);
        $this->comments[] = $comment;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}
