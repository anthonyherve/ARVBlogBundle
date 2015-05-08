<?php

namespace ARV\BlogBundle\Tests\Form\Type;


use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Form\Type\TagType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class TagTypeTest
 * @package ARV\BlogBundle\Tests\Form\Type
 */
class TagTypeTest extends TypeTestCase
{

    /**
     *
     */
    public function testSubmitData()
    {
        $data = array(
            'name' => 'New tag'
        );

        $type = new TagType();
        $form = $this->factory->create($type);

        $object = new Tag();
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($data as $key => $value) {
            $accessor->setValue($object, $key, $value);
        }

        // submit the data to the form directly
        $form->submit($data);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

}
