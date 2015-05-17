<?php

namespace ARV\BlogBundle\Twig\Extension;


use ARV\BlogBundle\ARVBlogParameters;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GlobalsExtension extends \Twig_Extension
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(
            'arblog_is_secure' =>
                $this->container->getParameter(ARVBlogParameters::IS_SECURE),
            'arvblog_article_content_editor' =>
                $this->container->getParameter(ARVBlogParameters::CONTENT_EDITOR),
            'arvblog_article_need_validation' =>
                $this->container->getParameter(ARVBlogParameters::NEED_VALIDATION),
            'arvblog_comment_waiting_time' =>
                $this->container->getParameter(ARVBlogParameters::WAITING_TIME),
            'arvblog_comment_display_email' =>
                $this->container->getParameter(ARVBlogParameters::DISPLAY_EMAIL),
            'arvblog_comment_write_as_anonymous' =>
                $this->container->getParameter(ARVBlogParameters::WRITE_AS_ANONYMOUS)
        );
    }

    public function getName()
    {
        return 'ARVBlogBundle:GlobalsExtension';
    }

}