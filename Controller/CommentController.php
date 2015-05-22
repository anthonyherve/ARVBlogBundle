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
     * @param Request $request
     * @param Article $article
     * @return array
     */
    public function listAction(Request $request, Article $article = null)
    {
        $comments = $this->get(ARVBlogServices::COMMENT_MANAGER)->getAll($request->query->get('page', 1), $article);
        $deleteForms = $this->get(ARVBlogServices::COMMENT_FORM)->deleteForms($comments);

        return array(
            'comments' => $comments,
            'delete_forms' => $deleteForms,
            'article' => $article
        );
    }

    /**
     * Manage comments.
     * @Template
     * @param Request $request
     * @return array
     */
    public function manageAction(Request $request)
    {
        $this->checkRight();

        $comments = $this->get(ARVBlogServices::COMMENT_MANAGER)->getAll($request->query->get('page', 1));
        $deleteForms = $this->get(ARVBlogServices::COMMENT_FORM)->deleteForms($comments);

        return array(
            'comments' => $comments,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * Display new form.
     * @Template
     * @ParamConverter("article", class="ARVBlogBundle:Article", options={"id"="id_article"})
     * @param Article $article
     * @return array
     */
    public function newAction(Article $article = null)
    {
        $this->checkCreateRight();

        $comment = new Comment();
        if ($article !== null) {
            $comment->setArticle($article);
        }
        $form = $this->get(ARVBlogServices::COMMENT_FORM)->createForm($comment);

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
        $this->checkCreateRight();

        $comment = new Comment();
        $form = $this->get(ARVBlogServices::COMMENT_FORM)->createForm($comment);
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
        $deleteForm = $this->get(ARVBlogServices::COMMENT_FORM)->deleteForm($comment);

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
        if (!$this->checkUpdateRight($comment)) {
            $this->addFlash('danger', 'arv.blog.exception.comment_edition');
            return $this->redirect($this->generateUrl('arv_blog_home'));
        }

        $editForm = $this->get(ARVBlogServices::COMMENT_FORM)->editForm($comment);
        $deleteForm = $this->get(ARVBlogServices::COMMENT_FORM)->deleteForm($comment);

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
        if (!$this->checkUpdateRight($comment)) {
            $this->addFlash('danger', 'arv.blog.exception.comment_edition');
            return $this->redirect($this->generateUrl('arv_blog_home'));
        }

        $deleteForm = $this->get(ARVBlogServices::COMMENT_FORM)->deleteForm($comment);
        $editForm = $this->get(ARVBlogServices::COMMENT_FORM)->editForm($comment);
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
        $this->checkRight();

        $form = $this->get(ARVBlogServices::COMMENT_FORM)->deleteForm($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(ARVBlogServices::COMMENT_MANAGER)->delete($comment);
            $this->addFlash('success', 'arv.blog.flash.success.comment_deleted');
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return $this->redirect($this->generateUrl('arv_blog_comment_manage'));
    }

    /**
     * Check right of user.
     */
    private function checkRight() {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }
    }

    /**
     * Check right to update comment.
     * @param Comment $comment
     * @return bool
     */
    private function checkUpdateRight(Comment $comment) {
        // Check if user can write anonymously a comment
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            if (!$this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)) {
                $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_USER, null, 'arv.blog.exception.forbidden');
                if ($comment->getUser() != $this->get('security.token_storage')->getToken()->getUser()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check right to create comment.
     */
    private function checkCreateRight() {
        // Check if user can write anonymously a comment
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            if (!$this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)) {
                $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_USER, null, 'arv.blog.exception.forbidden');
            }
        }
    }

}
