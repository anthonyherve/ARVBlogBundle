<?php

namespace ARV\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CommentType
 * @package ARV\BlogBundle\Form\Type
 */
class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array('label' => 'arv.blog.form.label.comment.content'))
            ->add('article', 'entity', array(
                    'class' => 'ARVBlogBundle:Article',
                    'property' => 'title',
                    'label' => 'arv.blog.form.label.comment.article'
                )
            )
        ;

        if ($options['display_email']) {
            $builder->add('email', 'email', array('label' => 'arv.blog.form.label.comment.email'));
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ARV\BlogBundle\Entity\Comment',
            'display_email' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'arv_blogbundle_comment';
    }
}
