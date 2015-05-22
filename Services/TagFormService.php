<?php

namespace ARV\BlogBundle\Services;


use ARV\BlogBundle\Entity\Article;
use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Form\Type\ArticleType;
use ARV\BlogBundle\Form\Type\TagType;

/**
 * Class TagFormService
 * @package ARV\BlogBundle\Services
 */
class TagFormService extends FormService
{

    /**
     * Get create form for a $tag.
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function createForm(Tag $tag)
    {
        return $this->getCreateForm(
            new TagType(), $tag, $this->router->generate('arv_blog_tag_create'),
            $this->translator->trans('arv.blog.form.button.add')
        );
    }

    /**
     * Get edit form for a $tag.
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function editForm(Tag $tag)
    {
        return $this->getEditForm(
            new TagType(), $tag,
            $this->router->generate('arv_blog_tag_update', array('id' => $tag->getId())),
            $this->translator->trans('arv.blog.form.button.edit')
        );
    }

    /**
     * Get a delete form for $tag.
     * @param Tag $tag
     * @return mixed
     */
    public function deleteForm(Tag $tag)
    {
        return $this->getDeleteForm(
            $this->router->generate('arv_blog_tag_delete', array('id' => $tag->getId())),
            $this->translator->trans('arv.blog.form.button.delete')
        );
    }

    /**
     * Get delete forms for $tags.
     * @param $tags
     * @return array
     */
    public function deleteForms($tags)
    {
        return $this->getDeleteForms(
            $tags,
            'arv_blog_tag_delete',
            $this->translator->trans('arv.blog.form.button.delete')
        );
    }

}
