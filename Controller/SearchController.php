<?php

namespace ARV\BlogBundle\Controller;

use ARV\BlogBundle\ARVBlogServices;
use ARV\BlogBundle\Entity\Article;
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
     * Search by tag.
     * @Template
     * @param $tag
     * @return array
     */
    public function tagAction(Request $request, $tag)
    {

        $articles = $this->get(ARVBlogServices::ARTICLE_MANAGER)->searchByTag($tag, $request->query->get('page', 1), true);
        $deleteForms = $this->getDeleteForms($articles);

        return array(
            'articles' => $articles,
            'delete_forms' => $deleteForms
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

    /**
     * Create list of delete forms.
     * @param $articles
     * @return array
     */
    private function getDeleteForms($articles)
    {
        $deleteForms = array();
        foreach ($articles as $article) {
            $deleteForms[$article->getId()] = $this->getDeleteForm($article)->createView();
        }
        return $deleteForms;
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\Form\Form
     */
    private function getDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('arv_blog_article_delete', array('id' => $article->getId(), 'slug' => $article->getSlug())))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array('label' => $this->get('translator')->trans('arv.blog.form.button.delete'))
            )
            ->getForm();
    }

}

