<?php

namespace ARV\BlogBundle\Services;


use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Form\Type\CommentType;

/**
 * Class CommentFormService
 * @package ARV\BlogBundle\Services
 */
class CommentFormService extends FormService
{

    /**
     * @var boolean
     */
    private $displayEmail;

    /**
     * Construct CommentFormService.
     * @param $displayEmail
     */
    public function __construct($displayEmail)
    {
        $this->displayEmail = $displayEmail;
    }

    /**
     * @return array
     */
    private function getFormOptions() {
        return array(
            'display_email' => $this->displayEmail
        );
    }

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    public function createForm(Comment $comment)
    {
        return $this->getCreateForm(
            new CommentType(), $comment, $this->router->generate('arv_blog_comment_create'),
            $this->translator->trans('arv.blog.form.button.add'),
            $this->getFormOptions()
        );
    }

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    public function editForm(Comment $comment)
    {
        return $this->getEditForm(
            new CommentType(), $comment,
            $this->router->generate('arv_blog_comment_update', array('id' => $comment->getId())),
            $this->translator->trans('arv.blog.form.button.edit'),
            $this->getFormOptions()
        );
    }

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    public function deleteForm(Comment $comment)
    {
        return $this->getDeleteForm(
            $this->router->generate('arv_blog_comment_delete', array('id' => $comment->getId())),
            $this->translator->trans('arv.blog.form.button.delete')
        );
    }

    /**
     * Create list of delete forms.
     * @param $comments
     * @return array
     */
    public function deleteForms($comments)
    {
        return $this->getDeleteForms(
            $comments,
            'arv_blog_comment_delete',
            $this->translator->trans('arv.blog.form.button.delete')
        );
    }

}
