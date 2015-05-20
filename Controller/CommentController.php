<?php

namespace ARV\BlogBundle\Controller;

use ARV\BlogBundle\ARVBlogParameters;
use ARV\BlogBundle\ARVBlogRoles;
use ARV\BlogBundle\ARVBlogServices;
use ARV\BlogBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ARV\BlogBundle\Entity\Comment;
use ARV\BlogBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CommentController
 * @package ARV\BlogBundle\Controller
 */
class CommentController extends Controller
{

    /**
     * List of comments.
     * @Template
     * @ParamConverter("article", class="ARVBlogBundle:Article", options={"id"="id_article"})
     * @param Article $article
     * @return array
     */
    public function listAction(Article $article = null)
    {
        $comments = $this->get(ARVBlogServices::COMMENT_MANAGER)->getAll($article);
        $deleteForms = $this->getDeleteForms($comments);

        return array(
            'comments' => $comments,
            'delete_forms' => $deleteForms,
            'article' => $article
        );
    }

    /**
     * Manage comments.
     * @Template
     * @return array
     */
    public function manageAction()
    {
        $comments = $this->get(ARVBlogServices::COMMENT_MANAGER)->getAll();
        $deleteForms = $this->getDeleteForms($comments);

        return array(
            'comments' => $comments,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * Display new form.
     * @Template
     * @ParamConverter("article", class="ARVBlogBundle:Article", options={"id"="id_article"})
     * @return array
     */
    public function newAction(Article $article = null)
    {
        // Check if user can write anonymously a comment
        if (!$this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_USER, null, 'arv.blog.exception.forbidden');
        }

        $comment = new Comment();
        if ($article !== null) {
            $comment->setArticle($article);
        }
        $form = $this->getCreateForm($comment);

        return array(
            'comment' => $comment,
            'form' => $form->createView(),
        );
    }

    /**
     * Manage new form and create comment.
     * @Template("ARVBlogBundle:Comment:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        // Check if user can write anonymously a comment
        if (!$this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_USER, null, 'arv.blog.exception.forbidden');
        }

        $comment = new Comment();
        $form = $this->getCreateForm($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $minutesToWait = $this->container->getParameter(ARVBlogParameters::WAITING_TIME);
            $ip = $request->getClientIp();
            // Check if a comment with this IP was posted less than $minutesToWait
            if (!$this->get(ARVBlogServices::COMMENT_MANAGER)->existByDateAndIp($minutesToWait, $ip)) {
                $comment->setIp($ip);

                if ($this->get('security.authorization_checker')->isGranted(ARVBlogRoles::ROLE_USER)) {
                    $comment->setUser($this->get('security.token_storage')->getToken()->getUser());
                }

                $this->get(ARVBlogServices::COMMENT_MANAGER)->save($comment);
                $this->addFlash('success', 'arv.blog.flash.success.comment_created');

                return $this->redirect($this->generateUrl('arv_blog_comment_manage'));
            } else {
                $this->addFlash('danger',
                    $this->get('translator')->trans(
                        'arv.blog.flash.error.comment.already_posted',
                        array('%ip%' => $ip, '%minutes%' => $minutesToWait)
                    )
                );
            }
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return array(
            'comment' => $comment,
            'form' => $form->createView(),
        );
    }


    /**
     * Display a comment.
     * @Template
     * @param Comment $comment
     * @return array
     */
    public function showAction(Comment $comment)
    {
        $deleteForm = $this->getDeleteForm($comment);

        return array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Display edit form.
     * @Template
     * @param Comment $comment
     * @return array
     */
    public function editAction(Comment $comment)
    {
        // Check if user can write anonymously a comment
        if (!$this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_USER, null, 'arv.blog.exception.forbidden');
            if ($comment->getUser() != $this->get('security.token_storage')->getToken()->getUser()) {
                $this->addFlash('danger', 'arv.blog.exception.comment_edition');
                return $this->redirect($this->generateUrl('arv_blog_home'));
            }
        }

        $editForm = $this->getEditForm($comment);
        $deleteForm = $this->getDeleteForm($comment);

        return array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Manage edit form and edit comment.
     * @Template("ARVBlogBundle:Comment:edit.html.twig")
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function updateAction(Request $request, Comment $comment)
    {
        // Check if user can write anonymously a comment
        if (!$this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_USER, null, 'arv.blog.exception.forbidden');
            if ($comment->getUser() != $this->get('security.token_storage')->getToken()->getUser()) {
                $this->addFlash('danger', 'arv.blog.exception.comment_edition');
                return $this->redirect($this->generateUrl('arv_blog_home'));
            }
        }

        $deleteForm = $this->getDeleteForm($comment);
        $editForm = $this->getEditForm($comment);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $comment->setIp($request->getClientIp());
            $this->get(ARVBlogServices::COMMENT_MANAGER)->save($comment);
            $this->addFlash('success', 'arv.blog.flash.success.comment_edited');

            return $this->redirect($this->generateUrl('arv_blog_comment_manage'));
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Delete a comment.
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->getDeleteForm($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(ARVBlogServices::COMMENT_MANAGER)->delete($comment);
            $this->addFlash('success', 'arv.blog.flash.success.comment_deleted');
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return $this->redirect($this->generateUrl('arv_blog_comment_manage'));
    }


    // ****************
    // PRIVATE METHODS
    // ****************

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    private function getCreateForm(Comment $comment)
    {
        $form = $this->createForm(new CommentType(), $comment, array(
            'action' => $this->generateUrl('arv_blog_comment_create'),
            'method' => 'POST',
            'display_email' => $this->container->getParameter(ARVBlogParameters::DISPLAY_EMAIL)
        ));

        $form->add('submit', 'submit',
            array('label' => $this->get('translator')->trans('arv.blog.form.button.add'))
        );

        return $form;
    }

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    private function getEditForm(Comment $comment)
    {
        $form = $this->createForm(new CommentType(), $comment, array(
            'action' => $this->generateUrl('arv_blog_comment_update', array('id' => $comment->getId())),
            'method' => 'PUT',
            'display_email' => $this->container->getParameter(ARVBlogParameters::DISPLAY_EMAIL)
        ));

        $form->add('submit', 'submit',
            array('label' => $this->get('translator')->trans('arv.blog.form.button.edit'))
        );

        return $form;
    }

    /**
     * @param Comment $comment
     * @return \Symfony\Component\Form\Form
     */
    private function getDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('arv_blog_comment_delete', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array('label' => $this->get('translator')->trans('arv.blog.form.button.delete'))
            )
            ->getForm();
    }

    /**
     * Create list of delete forms.
     * @param $comments
     * @return array
     */
    private function getDeleteForms($comments)
    {
        $deleteForms = array();
        foreach ($comments as $comment) {
            $deleteForms[$comment->getId()] = $this->getDeleteForm($comment)->createView();
        }
        return $deleteForms;
    }

}

