<?php

namespace ARV\BlogBundle\Tests\DependencyInjection;

use ARV\BlogBundle\DependencyInjection\ARVBlogExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class ARVBlogExtensionTest extends \PHPUnit_Framework_TestCase
{

    /** @var ContainerBuilder */
    protected $configuration;

    /**
     *
     */
    public function test_load_config()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_is_secure_is_valid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['is_secure'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_content_editor_is_valid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['article']['content_editor'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_need_validation_is_valid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['article']['need_validation'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_waiting_time_is_integer()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['waiting_time'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_waiting_time_is_valid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['waiting_time'] = -1;
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_display_email_is_valid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['display_email'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function test_load_config_throws_exception_unless_write_as_anonymous_is_valid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['write_as_anonymous'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * Get default configuration.
     * @return array
     */
    private function getDefaultConfig()
    {
        $yaml = <<<EOF
is_secure: false
article:
    content_editor: tinymce
    need_validation: true
comment:
    waiting_time: 5
    display_email: true
    write_as_anonymous: false
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

}
