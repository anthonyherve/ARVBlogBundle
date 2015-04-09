<?php
/**
 * Created by PhpStorm.
 * User: ahe
 * Date: 09/04/2015
 * Time: 13:19
 */

namespace ARV\BlogBundle\Tests\Controller;


use Liip\FunctionalTestBundle\Test\WebTestCase;

class AbstractControllerTest extends WebTestCase {

    public function setUp()
    {
        $this->loadFixtures(array());
    }

}