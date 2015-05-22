<?php

namespace ARV\BlogBundle\Services;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Form\Type\ArticleType;

/**
 * Class ArticleFormService
 * @package ARV\BlogBundle\Services
 */
class ArticleFormService extends FormService
{

    /**
     * @var string
     */
    private $contentEditor;
    /**
     * @var boolean
     */
    private $needValidation;

    /**
     * Construct ArticleFormService.
     * @param $contentEditor
     * @param $needValidation
     */
    public function __construct($contentEditor, $needValidation)
    {
        $this->contentEditor = $contentEditor;
        $this->needValidation = $needValidation;
    }

    /**
     * @return array
     */
    private function getFormOptions() {
        return array(
            'content_editor' => $this->contentEditor,
            'need_validation' => $this->needValidation
        );
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\Form\Form
     */
    public function createForm(Article $article)
    {
        return $this->getCreateForm(
            new ArticleType(), $article, $this->router->generate('arv_blog_article_create'),
            $this->translator->trans('arv.blog.form.button.add'),
            $this->getFormOptions()
        );
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\Form\Form
     */
    public function editForm(Article $article)
    {
        return $this->getEditForm(
            new ArticleType(), $article,
            $this->router->generate('arv_blog_article_update', array('id' => $article->getId(), 'slug' => $article->getSlug())),
            $this->translator->trans('arv.blog.form.button.edit'),
            $this->getFormOptions()
        );
    }

    /**
     * @param Article $article
     * @return \Symfony\Component\Form\Form
     */
    public function deleteForm(Article $article)
    {
        return $this->getDeleteForm(
            $this->router->generate('arv_blog_article_delete', array('id' => $article->getId(), 'slug' => $article->getSlug())),
            $this->translator->trans('arv.blog.form.button.delete')
        );
    }

    /**
     * Create list of delete forms.
     * @param $articles
     * @return array
     */
    public function deleteForms($articles)
    {
        return $this->getDeleteForms(
            $articles,
            'arv_blog_article_delete',
            $this->translator->trans('arv.blog.form.button.delete'),
            true
        );
    }

}
