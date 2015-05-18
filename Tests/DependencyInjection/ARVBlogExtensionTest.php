<?php

namespace ARV\BlogBundle\Tests\DependencyInjection;

use ARV\BlogBundle\DependencyInjection\ARVBlogExtension;
use ARV\BlogBundle\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class ARVBlogExtensionTest extends \PHPUnit_Framework_TestCase
{

    /** @var ContainerBuilder */
    protected $configuration;

    /**
     *
     */
    public function testLoadDefaultConfiguration()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessIsSecureIsValid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['is_secure'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessContentEditorIsValid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['article']['content_editor'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessNeedValidationIsValid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['article']['need_validation'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessWaitingTimeIsInteger()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['waiting_time'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessWaitingTimeIsValid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['waiting_time'] = -1;
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessDisplayEmailIsValid()
    {
        $loader = new ARVBlogExtension();
        $config = $this->getDefaultConfig();
        $config['comment']['display_email'] = 'blabla';
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadConfigurationThrowsExceptionUnlessWriteAsAnonymousIsValid()
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
