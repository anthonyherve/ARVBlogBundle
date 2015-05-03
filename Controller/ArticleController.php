<?php

namespace ARV\BlogBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Template
     * @param Request $request
     * @param string $search
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $search = '')
    {
        $articles = $this->get('arv_blog_manager_article')->search($search);
        $deleteForms = $this->getDeleteForms($articles);

        return array(
            'articles' => $articles,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manageAction()
    {
        $articles = $this->get('arv_blog_manager_article')->getAll();
        $deleteForms = $this->getDeleteForms($articles);

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
        $article = new Article();
        $form = $this->getCreateForm($article);
        $form->handleRequest($request);
        $session = $this->get('session');

        if ($form->isValid()) {
            // Check if tag already exists in database
            $tags = new ArrayCollection();
            foreach ($article->getTags() as $tag) {
                $tagFromDB = $this->get('arv_blog_manager_tag')->getRepository()->findOneByName(strtolower($tag->getName()));
                if ($tagFromDB === null) {
                    $tags[] = $tag;
                } else {
                    $tags[] = $tagFromDB;
                }
            }
            $article->setTags($tags);

            $this->get('arv_blog_manager_article')->save($article);


            $session->getFlashBag()->add('success', "L'article a bien été ajouté.");
            return $this->redirect($this->generateUrl('arv_blog_article_show', array('id' => $article->getId(), 'slug' => $article->getSlug())));
        } else {
            $session->getFlashBag()->add('danger', "Le formulaire n'est pas valide.");
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
            'delete_form' => $deleteForm->createView(),
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
        $editForm = $this->getEditForm($article);
        $deleteForm = $this->getDeleteForm($article);

        return array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->getDeleteForm($article);
        $editForm = $this->getEditForm($article);
        $editForm->handleRequest($request);
        $session = $this->get('session');

        if ($editForm->isValid()) {
            $this->get('arv_blog_manager_article')->save($article);
            $session->getFlashBag()->add('success', "L'article a bien été modifié.");
            return $this->redirect($this->generateUrl('arv_blog_article_show', array('id' => $article->getId(), 'slug' => $article->getSlug())));
        } else {
            $session->getFlashBag()->add('danger', "Le formulaire n'est pas valide.");
        }

        return array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $form = $this->getDeleteForm($article);
        $form->handleRequest($request);
        $session = $this->get('session');

        if ($form->isValid()) {
            $this->get('arv_blog_manager_article')->delete($article);
            $session->getFlashBag()->add('success', "L'article a bien été supprimé.");
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
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter'));

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
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

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
            ->add('submit', 'submit', array('label' => 'Supprimer'))
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
