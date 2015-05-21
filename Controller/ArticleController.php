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
        $foundArticles = $this->get(ARVBlogServices::ARTICLE_MANAGER)->search($search, true);
        $deleteForms = $this->getDeleteForms($foundArticles);

        $articles = $this->get('knp_paginator')->paginate(
            $foundArticles,
            $page,
            2
        );

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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $foundArticles = $this->get(ARVBlogServices::ARTICLE_MANAGER)->getAll();
        $deleteForms = $this->getDeleteForms($foundArticles);

        $articles = $this->get('knp_paginator')->paginate(
            $foundArticles,
            $request->query->get('page', 1),
            2
        );

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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $form = $this->getCreateForm(new Article());

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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $article = new Article();
        $form = $this->getCreateForm($article);
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
        $deleteForm = $this->getDeleteForm($article);

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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $editForm = $this->getEditForm($article);
        $deleteForm = $this->getDeleteForm($article);

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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $deleteForm = $this->getDeleteForm($article);
        $editForm = $this->getEditForm($article);
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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $form = $this->getDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(ARVBlogServices::ARTICLE_MANAGER)->delete($article);
            $this->addFlash('success', 'arv.blog.flash.success.article_deleted');
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return $this->redirect($this->generateUrl('arv_blog_article_manage'));
    }

    // ****************
    // PRIVATE METHODS
    // ****************

    /**
     * @param Article $entity
     * @return \Symfony\Component\Form\Form
     */
    private function getCreateForm(Article $entity)
    {
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('arv_blog_article_create'),
            'method' => 'POST',
            'content_editor' => $this->container->getParameter(ARVBlogParameters::CONTENT_EDITOR),
            'need_validation' => $this->container->getParameter(ARVBlogParameters::NEED_VALIDATION)
        ));

        $form->add('submit', 'submit',
            array('label' => $this->get('translator')->trans('arv.blog.form.button.add'))
        );

        return $form;
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\Form\Form
     */
    private function getEditForm(Article $article)
    {
        $form = $this->createForm(new ArticleType(), $article, array(
            'action' => $this->generateUrl('arv_blog_article_update', array('id' => $article->getId(), 'slug' => $article->getSlug())),
            'method' => 'PUT',
            'content_editor' => $this->container->getParameter(ARVBlogParameters::CONTENT_EDITOR),
            'need_validation' => $this->container->getParameter(ARVBlogParameters::NEED_VALIDATION)
        ));

        $form->add('submit', 'submit',
            array('label' => $this->get('translator')->trans('arv.blog.form.button.edit'))
        );

        return $form;
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
}
