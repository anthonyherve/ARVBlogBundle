<?php

namespace ARV\BlogBundle\Tests\Form\Type;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Form\Type\ArticleType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ArticleTypeTest
 * @package ARV\BlogBundle\Tests\Form\Type
 */
class ArticleTypeTest extends TypeTestCase
{

    /**
     * @dataProvider getValidTestData
     * @param $data
     */
    public function testSubmitData($data)
    {
        $type = new ArticleType();
        $form = $this->factory->create($type);

        $object = new Article();
        $object->setTitle($data['title']);
        $object->setContent($data['content']);
        foreach ($data['tags'] as $tag) {
            $object->addTag(new Tag($tag['name']));
        }

        $form->submit($data);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }

    /**
     * @return array
     */
    public function getValidTestData()
    {
        return array(
            array(
                'data' => array(
                    'title' => 'Title for article',
                    'content' => 'Content for article',
                    'tags' => array(
                        array('name' => 'tag10'),
                        array('name' => 'tag11')
                    )
                ),
            ),
            array(
                'data' => array(
                    'title' => 'Title for article',
                    'content' => 'Content for article',
                    'tags' => array()
                ),
            )
        );
    }


}
