<?php

namespace ARV\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Form\Type\TagType;

/**
 * Tag controller.
 *
 */
class TagController extends Controller
{

    /**
     * @Template
     * @return array
     */
    public function manageAction()
    {
        $tags = $this->get('arv_blog_manager_tag')->getAll();
        $deleteForms = $this->getDeleteForms($tags);

        return array(
            'tags' => $tags,
            'delete_forms' => $deleteForms
        );
    }

    /**
     * @Template
     * @return array
     */
    public function newAction()
    {
        $tag = new Tag();
        $form   = $this->getCreateForm($tag);

        return array(
            'tag' => $tag,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template("ARVBlogBundle:Tag:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->getCreateForm($tag);
        $form->handleRequest($request);
        $session = $this->get('session');

        if ($form->isValid()) {
            $this->get('arv_blog_manager_tag')->save($tag);
            $session->getFlashBag()->add('success', "Le tag a bien été ajouté.");

            return $this->redirect($this->generateUrl('arv_blog_tag'));
        } else {
            $session->getFlashBag()->add('danger', "Le formulaire n'est pas valide.");
        }

        return array(
            'tag' => $tag,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template
     * @param Tag $tag
     * @return array
     */
    public function showAction(Tag $tag)
    {
        $deleteForm = $this->getDeleteForm($tag);

        return array(
            'tag'      => $tag,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template
     * @param Tag $tag
     * @return array
     */
    public function editAction(Tag $tag)
    {
        $editForm = $this->getEditForm($tag);
        $deleteForm = $this->getDeleteForm($tag);

        return array(
            'tag'      => $tag,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Template("ARVBlogBundle:Tag:edit.html.twig")
     * @param Request $request
     * @param Tag $tag
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Tag $tag)
    {
        $deleteForm = $this->getDeleteForm($tag);
        $editForm = $this->getEditForm($tag);
        $editForm->handleRequest($request);
        $session = $this->get('session');

        if ($editForm->isValid()) {
            $this->get('arv_blog_manager_tag')->save($tag);
            $session->getFlashBag()->add('success', "Le tag a bien été modifié.");

            return $this->redirect($this->generateUrl('arv_blog_tag'));
        } else {
            $session->getFlashBag()->add('danger', "Le formulaire n'est pas valide.");
        }

        return array(
            'tag'         => $tag,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Delete a tag.
     * @param Request $request
     * @param Tag $tag
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Tag $tag)
    {
        $form = $this->getDeleteForm($tag);
        $form->handleRequest($request);
        $session = $this->get('session');

        if ($form->isValid()) {
            $this->get('arv_blog_manager_tag')->delete($tag);
            $session->getFlashBag()->add('success', "Le tag a bien été supprimé.");
        }

        return $this->redirect($this->generateUrl('arv_blog_tag'));
    }


    // ****************
    // PRIVATE METHODS
    // ****************

    /**
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form
     */
    private function getCreateForm(Tag $tag)
    {
        $form = $this->createForm(new TagType(), $tag, array(
            'action' => $this->generateUrl('arv_blog_tag_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter'));

        return $form;
    }

    /**
     *
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form
     */
    private function getEditForm(Tag $tag)
    {
        $form = $this->createForm(new TagType(), $tag, array(
            'action' => $this->generateUrl('arv_blog_tag_update', array('id' => $tag->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * @param Tag $tag
     * @return \Symfony\Component\Form\Form
     */
    private function getDeleteForm(Tag $tag)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('arv_blog_tag_delete', array('id' => $tag->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
        ;
    }

    /**
     * Create list of delete forms.
     * @param $tags
     * @return array
     */
    private function getDeleteForms($tags)
    {
        $deleteForms = array();
        foreach ($tags as $tag) {
            $deleteForms[$tag->getId()] = $this->getDeleteForm($tag)->createView();
        }
        return $deleteForms;
    }

}

