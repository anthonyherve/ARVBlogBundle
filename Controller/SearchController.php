<?php

namespace ARV\BlogBundle\Controller;

use ARV\BlogBundle\Form\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SearchController
 * @package ARV\BlogBundle\Controller
 */
class SearchController extends Controller
{

    /**
     * Display search form.
     * @Template
     * @param Request $request
     */
    public function formAction(Request $request)
    {
        $form = $this->createSearchForm();

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Search something.
     * @Template
     * @param Request $request
     */
    public function searchAction(Request $request)
    {
        $form = $this->createSearchForm();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            $data = $form->getData();
        }

        return array(
            'form' => $form->createView(),
            'search' => $data['query']
        );
    }

    /**
     * Create search form.
     * @return \Symfony\Component\Form\Form
     */
    private function createSearchForm()
    {
        return $this->createForm(new SearchType(), null, array(
            'action' => $this->generateUrl('arv_blog_search_result'),
            'method' => 'POST',
        ));
    }

}

