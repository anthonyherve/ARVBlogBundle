<?php

namespace ARV\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{

    /**
     * @Template
     */
    public function indexAction()
    {
        return array();
    }

}

