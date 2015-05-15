<?php

namespace ARV\BlogBundle\Form\Type;

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
            ->add('content', 'textarea', array(
                'label' => 'arv.blog.form.label.article.content',
                'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')
            ))
            ->add('datePublication', 'datetime', array('label' => 'arv.blog.form.label.article.publication_date'))
            ->add('tags', 'collection', array(
                'label' => 'arv.blog.form.label.article.tags',
                'type' => new TagType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ARV\BlogBundle\Entity\Article'
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
