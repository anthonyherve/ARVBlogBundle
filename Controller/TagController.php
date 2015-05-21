<?php

namespace ARV\BlogBundle\Controller;

use ARV\BlogBundle\ARVBlogServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use ARV\BlogBundle\Entity\Tag;
use ARV\BlogBundle\Form\Type\TagType;

/**
 * Class TagController
 * @package ARV\BlogBundle\Controller
 */
class TagController extends Controller
{

    /**
     * Manage tags.
     * @Template
     * @return array
     */
    public function manageAction(Request $request)
    {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $foundTags = $this->get(ARVBlogServices::TAG_MANAGER)->getAll();
        $deleteForms = $this->getDeleteForms($foundTags);

        $tags = $this->get('knp_paginator')->paginate(
            $foundTags,
            $request->query->get('page', 1),
            2
        );

        return array(
            'tags' => $tags,
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

        $tag = new Tag();
        $form = $this->getCreateForm($tag);

        return array(
            'tag' => $tag,
            'form' => $form->createView(),
        );
    }

    /**
     * Manage new form and create tag.
     * @Template("ARVBlogBundle:Tag:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $tag = new Tag();
        $form = $this->getCreateForm($tag);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(ARVBlogServices::TAG_MANAGER)->save($tag);
            $this->addFlash('success', 'arv.blog.flash.success.tag_created');

            return $this->redirect($this->generateUrl('arv_blog_tag_manage'));
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return array(
            'tag' => $tag,
            'form' => $form->createView(),
        );
    }

    /**
     * Display a tag.
     * @Template
     * @param Tag $tag
     * @return array
     */
    public function showAction(Tag $tag)
    {
        $deleteForm = $this->getDeleteForm($tag);

        return array(
            'tag' => $tag,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Display edit form.
     * @Template
     * @param Tag $tag
     * @return array
     */
    public function editAction(Tag $tag)
    {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $editForm = $this->getEditForm($tag);
        $deleteForm = $this->getDeleteForm($tag);

        return array(
            'tag' => $tag,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Manage edit form and edit tag.
     * @Template("ARVBlogBundle:Tag:edit.html.twig")
     * @param Request $request
     * @param Tag $tag
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, Tag $tag)
    {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $deleteForm = $this->getDeleteForm($tag);
        $editForm = $this->getEditForm($tag);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->get(ARVBlogServices::TAG_MANAGER)->save($tag);
            $this->addFlash('success', 'arv.blog.flash.success.tag_edited');

            return $this->redirect($this->generateUrl('arv_blog_tag_manage'));
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return array(
            'tag' => $tag,
            'edit_form' => $editForm->createView(),
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
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }

        $form = $this->getDeleteForm($tag);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get(ARVBlogServices::TAG_MANAGER)->delete($tag);
            $this->addFlash('success', 'arv.blog.flash.success.tag_deleted');
        } else {
            $this->addFlash('danger', 'arv.blog.flash.error.form_not_valid');
        }

        return $this->redirect($this->generateUrl('arv_blog_tag_manage'));
    }

    /**
     * Display tags cloud.
     * @Template
     * @return array
     */
    public function cloudAction()
    {
        return array(
            'tags' => $this->get(ARVBlogServices::TAG_REPOSITORY)->getCloud()
        );
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

        $form->add('submit', 'submit',
            array('label' => $this->get('translator')->trans('arv.blog.form.button.add'))
        );

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

        $form->add('submit', 'submit',
            array('label' => $this->get('translator')->trans('arv.blog.form.button.edit'))
        );

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
            ->add('submit', 'submit',
                array('label' => $this->get('translator')->trans('arv.blog.form.button.delete'))
            )
            ->getForm();
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

