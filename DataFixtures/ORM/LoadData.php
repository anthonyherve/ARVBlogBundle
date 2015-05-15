<?php
namespace ARV\BlogBundle\DataFixtures\ORM;

use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadData
 * @package ARV\BlogBundle\DataFixtures\ORM
 */
class LoadData implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Add tags
        $tag1 = new Tag('tag1');
        $manager->persist($tag1);
        $tag2 = new Tag('tag2');
        $manager->persist($tag2);
        $tag3 = new Tag('tag3');
        $manager->persist($tag3);
        $tag4 = new Tag('tag4');
        $manager->persist($tag4);
        $tag5 = new Tag('tag5');
        $manager->persist($tag5);
        $tag6 = new Tag('tag6');
        $manager->persist($tag6);
        $tag7 = new Tag('tag7');
        $manager->persist($tag7);
        $tag8 = new Tag('tag8');
        $manager->persist($tag8);
        $tag9 = new Tag('tag9');
        $manager->persist($tag9);

        // Add articles
        $article1 = new Article();
        $article1->setTitle("HTML Ipsum Presents");
        $article1->setContent("<p><strong>Pellentesque habitant morbi tristique</strong>
            senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam,
            feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet
            quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat
            eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum
            erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi.
            Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis
            tempus lacus enim ac dui. <a href='#'>Donec non enim</a> in turpis pulvinar facilisis.
            Ut felis.</p>");
        $article1->addTag($tag1);
        $article1->addTag($tag2);
        $article1->addTag($tag3);
        $article1->addTag($tag4);
        $article1->setDatePublication(new \DateTime());
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setTitle("Lorem Ipsum again");
        $article2->setContent("<h2>Header Level 2</h2>
            <ol>
               <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
               <li>Aliquam tincidunt mauris eu risus.</li>
            </ol>
            <blockquote>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna.
                Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa.
                Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis
                elit sit amet quam. Vivamus pretium ornare est.</p>
            </blockquote>
            <h3>Header Level 3</h3>
            <ul>
               <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
               <li>Aliquam tincidunt mauris eu risus.</li>
            </ul>");
        $article2->addTag($tag2);
        $article2->addTag($tag5);
        $article2->addTag($tag6);
        $article2->setDatePublication(new \DateTime());
        $manager->persist($article2);

        $article3 = new Article();
        $article3->setTitle("More lorem ipsum");
        $article3->setContent("<pre><code>
            #header h1 a {
                display: block;
                width: 300px;
                height: 80px;
            }
            </code></pre>");
        $article3->addTag($tag3);
        $article3->addTag($tag7);
        $article3->addTag($tag8);
        $article3->addTag($tag9);
        $now = new \DateTime();
        $article3->setDatePublication($now->add(new \DateInterval('P1D')));
        $manager->persist($article3);

        // Add comments
        $comment1 = new Comment();
        $comment1->setEmail("user@gmail.com");
        $comment1->setIp("192.168.0.1");
        $comment1->setContent("Pellentesque habitant morbi tristique senectus et netus et malesuada
            fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor
            sit amet, ante.");
        $comment1->setArticle($article1);
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setEmail("user@gmail.com");
        $comment2->setIp("192.168.0.1");
        $comment2->setContent("Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae
            est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.
            Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi.");
        $comment2->setArticle($article2);
        $manager->persist($comment2);

        $comment3 = new Comment();
        $comment3->setEmail("user@gmail.com");
        $comment3->setIp("192.168.0.2");
        $comment3->setContent("Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci,
            sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis.");
        $comment3->setArticle($article2);
        $manager->persist($comment3);

        $manager->flush();
    }

}
