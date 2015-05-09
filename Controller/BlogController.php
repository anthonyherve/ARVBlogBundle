<?php

namespace ARV\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class BlogController
 * @package ARV\BlogBundle\Controller
 */
class BlogController extends Controller
{

    /**
     * @Template
     */
    public function indexAction()
    {
        return array();
    }

}

