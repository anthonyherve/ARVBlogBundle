<?php

namespace ARV\BlogBundle\Controller;

use ARV\BlogBundle\ARVBlogParameters;
use ARV\BlogBundle\ARVBlogRoles;
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
        $this->checkRight();

        $tags = $this->get(ARVBlogServices::TAG_MANAGER)->getAll($request->query->get('page', 1));
        $deleteForms = $this->get(ARVBlogServices::TAG_FORM)->deleteForms($tags);

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
        $this->checkRight();

        $tag = new Tag();
        $form = $this->get(ARVBlogServices::TAG_FORM)->createForm($tag);

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
        $this->checkRight();

        $tag = new Tag();
        $form = $this->get(ARVBlogServices::TAG_FORM)->createForm($tag);
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
        $deleteForm = $this->get(ARVBlogServices::TAG_FORM)->deleteForm($tag);

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
        $this->checkRight();

        $editForm = $this->get(ARVBlogServices::TAG_FORM)->editForm($tag);
        $deleteForm = $this->get(ARVBlogServices::TAG_FORM)->deleteForm($tag);

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
        $this->checkRight();

        $deleteForm = $this->get(ARVBlogServices::TAG_FORM)->deleteForm($tag);
        $editForm = $this->get(ARVBlogServices::TAG_FORM)->editForm($tag);
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
        $this->checkRight();

        $form = $this->get(ARVBlogServices::TAG_FORM)->deleteForm($tag);
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

    /**
     * Check right of user.
     */
    private function checkRight() {
        if ($this->container->getParameter(ARVBlogParameters::IS_SECURE)) {
            $this->denyAccessUnlessGranted(ARVBlogRoles::ROLE_ADMIN, null, 'arv.blog.exception.forbidden');
        }
    }

}

