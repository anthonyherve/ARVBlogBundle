<?php

namespace ARV\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package ARV\BlogBundle\Controller
 */
class BlogController extends Controller
{

    /**
     * @Template
     */
    public function indexAction(Request $request)
    {
        return array(
            'page' => $request->query->get('page', 1)
        );
    }

}

