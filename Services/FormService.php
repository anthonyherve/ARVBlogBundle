<?php

namespace ARV\BlogBundle\Services;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FormService.
 * @package ARV\BlogBundle\Services
 */
abstract class FormService
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var FormFactory
     */
    protected $factory;

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param FormFactory $factory
     */
    public function setFormFactory(FormFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Create a create form.
     * @param AbstractType $formType
     * @param $entity
     * @param $url
     * @param $labelSubmit
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function getCreateForm(AbstractType $formType, $entity, $url, $labelSubmit, $options = array()) {
        $requiredOptions = array('action' => $url, 'method' => 'POST');
        $form = $this->factory->create($formType, $entity, array_merge($requiredOptions, $options));
        $form->add('submit', 'submit',
            array('label' => $labelSubmit)
        );

        return $form;
    }

    /**
     * Create an edit form.
     * @param AbstractType $formType
     * @param $entity
     * @param $url
     * @param $labelSubmit
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function getEditForm(AbstractType $formType, $entity, $url, $labelSubmit, $options = array())
    {
        $requiredOptions = array('action' => $url, 'method' => 'PUT');
        $form = $this->factory->create($formType, $entity, array_merge($requiredOptions, $options));
        $form->add('submit', 'submit',
            array('label' => $labelSubmit)
        );

        return $form;
    }

    /**
     * Create a delete form.
     * @param $url
     * @param $labelSubmit
     * @return mixed
     */
    protected function getDeleteForm($url, $labelSubmit)
    {
        return $this->factory->createBuilder('form')
            ->setAction($url)
            ->setMethod('DELETE')
            ->add('submit', 'submit',
                array('label' => $labelSubmit)
            )
            ->getForm();
    }

    /**
     * Create delete forms for a list of $entities.
     * @param $entities
     * @param $url
     * @param $labelSubmit
     * @param boolean $withSlug
     * @return array
     */
    protected function getDeleteForms($entities, $url, $labelSubmit, $withSlug = false)
    {
        $deleteForms = array();
        foreach ($entities as $entity) {
            $options['id'] = $entity->getId();
            if ($withSlug) {
                $options['slug'] = $entity->getSlug();
            }
            $this->router->generate($url, $options);
            $deleteForms[$entity->getId()] = $this->getDeleteForm($url, $labelSubmit)->createView();
        }
        return $deleteForms;
    }

}
