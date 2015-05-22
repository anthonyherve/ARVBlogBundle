<?php

namespace ARV\BlogBundle\Controller;

use ARV\BlogBundle\ARVBlogParameters;
use ARV\BlogBundle\ARVBlogRoles;
use ARV\BlogBundle\ARVBlogServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Form\Type\ArticleType;

/**
 * Class ArticleController.
 * @package ARV\BlogBundle\Controller
 */
class ArticleController extends Controller
{

    /**
     * List of articles.
     * @Template
     * @param Request $request
     * @param string $search
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $search = '', $page = 1)
    {
        $articles = $this->get(ARVBlogServices::ARTICLE_MANAGER)->search($search, true, $page);
        $deleteForms = $this->get(ARVBlogServices::ARTICLE_FORM)->deleteForms($articles);

        return array(
            'articles' => $articles,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * Manage articles.
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manageAction(Request $request)
    {
        $this->checkRight();

        $articles = $this->get(ARVBlogServices::ARTICLE_MANAGER)->getAll($request->query->get('page', 1));
        $deleteForms = $this->get(ARVBlogServices::ARTICLE_FORM)->deleteForms($articles);

        return array(
            'articles' => $articles,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * Display new form.
     * @Template
     * @return array
     */
    public function newAction()
    {
        $this->checkRight();

        $form = $this->get(ARVBlogServices::ARTICLE_FORM)->createForm(new Article());

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Manage new form and create an article.
     * @Template("ARVBlogBundle:Article:new.html.twig")
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $this->checkRight();

        $article = new Article();
        $form = $this->get(ARVBlogServices::ARTICLE_FORM)->createForm($article);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $article->setTags($this->get(ARVBlogServices::TAG_MANAGER)->setTags($article->getTags()));

            if ($this->get('security.authorization_checker')->isGranted(ARVBlogRoles::ROLE_USER)) {
                $article->setUser($this->get('security.token_storage')->getToken()->getUser());
            }

            $this->get(ARVBlogServices::ARTICLE_MANAGER)->save($article);
            $this->addFlash('success', 'arv.blog.flash.success.article_created');

            return $this->redirect(
                $this->generateUrl(
                    'arv_blog_article_show',
                    array('id' => $article->getId(), 'slug' => $article->getSlug())
                )
            );
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Display an article.
     * @Template
     * @param Article $article
     * @return array
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->get(ARVBlogServices::ARTICLE_FORM)->deleteForm($article);

        return array(
            'article' => $article,
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Display edit form.
     * @Template
     * @param Article $article
     * @return array
     */
    public function editAction(Article $article)
    {
        $this->checkRight();

        $editForm = $this->get(ARVBlogServices::ARTICLE_FORM)->editForm($article);
        $deleteForm = $this->get(ARVBlogServices::ARTICLE_FORM)->deleteForm($article);

        return array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }


    /**
     * Manage edit form and update article.
     * @Template("ARVBlogBundle:Article:edit.html.twig")
     * @param Request $request
     * @param Article $article
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Article $article)
    {
        $this->checkRight();

        $deleteForm = $this->get(ARVBlogServices::ARTICLE_FORM)->deleteForm($article);
        $editForm = $this->get(ARVBlogServices::ARTICLE_FORM)->editForm($article);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $article->setTags($this->get(ARVBlogServices::TAG_MANAGER)->setTags($article->getTags()));
            $this->get(ARVBlogServices::ARTICLE_MANAGER)->save($article);
            $this->addFlash('success', 'arv.blog.flash.success.article_edited');
            return $this->redirect(
                $this->generateUrl(
                    'arv_blog_article_show',
                    array('id' => $article->getId(), 'slug' => $article->getSlug())
                )
            );
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Delete an article.
     * @param Request $request
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Article $article)
    {
        $this->checkRight();

        $form = $this->get(ARVBlogServices::ARTICLE_FORM)->deleteForm($article);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(ARVBlogServices::ARTICLE_MANAGER)->delete($article);
            $this->addFlash('success', 'arv.blog.flash.success.article_deleted');
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return $this->redirect($this->generateUrl('arv_blog_article_manage'));
    }

    /**
     * Check right of user
     */
    private function checkRight() {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }
    }

}
