<?php

namespace ARV\BlogBundle\Form\Type;

use ARV\BlogBundle\ARVBlogParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ArticleType
 * @package ARV\BlogBundle\Form\Type
 */
class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'arv.blog.form.label.article.title'))
            ->add('tags', 'collection', array(
                'label' => false,
                'type' => new TagType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
        ;

        // Define content field depending on content_editor parameter
        if ($options['content_editor'] == ARVBlogParameters::EDITOR_NONE) {
            $builder->add('content', 'textarea', array(
                'label' => 'arv.blog.form.label.article.content'
            ));
        } elseif($options['content_editor'] == ARVBlogParameters::EDITOR_TINYMCE) {
            $builder->add('content', 'textarea', array(
                'label' => 'arv.blog.form.label.article.content',
                'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')
            ));
        }
        // Display or not datePublication field if validation is needed
        if ($options['need_validation']) {
            $builder
                ->add('datePublication', 'datetime',
                    array('label' => 'arv.blog.form.label.article.publication_date')
                );
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ARV\BlogBundle\Entity\Article',
            'content_editor' => 'none',
            'need_validation' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'arv_blogbundle_article';
    }
}
