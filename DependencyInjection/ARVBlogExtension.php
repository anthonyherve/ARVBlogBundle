<?php

namespace ARV\BlogBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 * Class ARVBlogExtension
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 * @package ARV\BlogBundle\DependencyInjection
 */
class ARVBlogExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('arv_blog.is_secure', $config['is_secure']);
        $container->setParameter('arv_blog.article.content_editor', $config['article']['content_editor']);
        $container->setParameter('arv_blog.article.need_validation', $config['article']['need_validation']);
        $container->setParameter('arv_blog.comment.waiting_time', $config['comment']['waiting_time']);
        $container->setParameter('arv_blog.comment.display_email', $config['comment']['display_email']);
        $container->setParameter('arv_blog.comment.write_as_anonymous', $config['comment']['write_as_anonymous']);

    }
}
