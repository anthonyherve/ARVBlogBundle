<?php

namespace ARV\BlogBundle\Controller;

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
     * @Template
     * @ParamConverter("article", class="ARVBlogBundle:Article", options={"id"="id_article"})
     * @return array
     */
    public function listAction(Article $article = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($article == null) {
            $comments = $em->getRepository('ARVBlogBundle:Comment')->findBy(array(), array('dateModification' => 'DESC'));
        } else {
            $comments = $em->getRepository('ARVBlogBundle:Comment')->findBy(array('article' => $article), array('dateModification' => 'DESC'));
        }
        $deleteForms = $this->getDeleteForms($comments);

        return array(
            'comments' => $comments,
            'delete_forms' => $deleteForms,
            'article' => $article
        );
    }

    /**
     * @Template
     * @return array
     */
    public function manageAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('ARVBlogBundle:Comment')->findAll();
        $deleteForms = $this->getDeleteForms($comments);

        return array(
            'comments' => $comments,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * @Template
     * @ParamConverter("article", class="ARVBlogBundle:Article", options={"id"="id_article"})
     * @return array
     */
    public function newAction(Article $article = null)
    {
        $comment = new Comment();
        if ($article != null) {
            $comment->setArticle($article);
        }
        $form = $this->getCreateForm($comment);

        return array(
            'comment' => $comment,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $comment = new Comment();
        $form = $this->getCreateForm($comment);
        $form->handleRequest($request);
        $session = $this->get('session');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->setIp($request->getClientIp());
            $em->persist($comment);
            $em->flush();
            $session->getFlashBag()->add('success', "Le commentaire a bien été ajouté.");

            return $this->redirect($this->generateUrl('arv_blog_comment'));
        } else {
            $session->getFlashBag()->add('danger', "Le formulaire n'est pas valide.");
        }

        return array(
            'comment' => $comment,
            'form' => $form->createView(),
        );
    }


    /**
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
     * @Template
     * @param Comment $comment
     * @return array
     */
    public function editAction(Comment $comment)
    {
        $editForm = $this->getEditForm($comment);
        $deleteForm = $this->getDeleteForm($comment);

        return array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("ARVBlogBundle:Comment:edit.html.twig")
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function updateAction(Request $request, Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->getDeleteForm($comment);
        $editForm = $this->getEditForm($comment);
        $editForm->handleRequest($request);
        $session = $this->get('session');

        if ($editForm->isValid()) {
            $comment->setIp($request->getClientIp());
            $em->persist($comment);
            $em->flush();
            $session->getFlashBag()->add('success', "Le commentaire a bien été modifié.");

            return $this->redirect($this->generateUrl('arv_blog_comment'));
        } else {
            $session->getFlashBag()->add('danger', "Le formulaire n'est pas valide.");
        }

        return array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->getDeleteForm($comment);
        $form->handleRequest($request);
        $session = $this->get('session');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
            $session->getFlashBag()->add('success', "Le commentaire a bien été modifié.");
        }

        return $this->redirect($this->generateUrl('arv_blog_comment'));
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
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter'));

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
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

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
            ->add('submit', 'submit', array('label' => 'Supprimer'))
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

