# Documentation of ARVBlogBundle

The ARVBlogBundle provides simple blog features with management of all necessary entities (Article, Tag, Comment).

## Prerequisites

This bundle requires Symfony 2.6+.

## Installation

1. Download bundle with composer
2. Enable bundle in AppKernel
3. Basic configuration
4. Routing
5. Update database schema
 
### 1) Download bundle with composer

Add ARVBlogBundle to your project running this command:

```
php composer.phar require arv/blog-bundle "dev-master"
```

Or this one if **_composer_** command is available:

```
composer require arv/blog-bundle "dev-master"
```

### 2) Enable bundles in AppKernel

Add necessary bundles in *app/Appkernel.php*:

```php
<?php
// app/AppKernel.php
...

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new ARV\BlogBundle\ARVBlogBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            ...
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }
    ...
}
```
    
### 3) Configuration

Translations are used. So you have to enable these:

```yaml
# app/config.yml
framework:
    translator:  { fallback: "%locale%" }
```

#### 3.1. ARVBlogBundle configuration

Here is default configuration that is **NOT MANDATORY** to put into config file.

```yaml
# app/config.yml

# ARVBlogBundle
arv_blog:
    base_template: ~
    user_class: ~
    is_secure: false
    article:
        content_editor: ~
        need_validation: false
    comment:
        waiting_time: 0
        display_email: true
        write_as_anonymous: true
```

By default this component is completely autonomous, but you will see in detail each parameter in the [configuration](configuration.md) section.

#### 3.2. External bundles

As this bundle uses other bundles, there is some basic configuration to do:

```yaml
# app/config.yml

# Twig Extension
services:
    twig.extension.text:
        class: Twig_Extensions_Extension_Text
        tags:
            - { name: twig.extension }
            
# StofDoctrineExtensions configuration
stof_doctrine_extensions:
    orm:
        default:
            sluggable:     true
            
# TinyMCE Bundle
stfalcon_tinymce:
    include_jquery:     true
    tinymce_jquery:     true
    selector:           ".tinymce"
    language:           %locale%
    theme:
        # Simple theme
        simple:
            menubar:    false
            statusbar:  false
        # Advanced theme
        advanced:
            menubar:    false
            statusbar:  false
            plugins:
                - "textcolor link preview autoresize"
            toolbar1:   "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
            toolbar2:   "link | forecolor backcolor | preview"
            
# KNPPaginator
knp_paginator:
    page_range: 5                      # default page range used in pagination control
    default_options:
        page_name: page                # page query parameter name
        sort_field_name: sort          # sort field query parameter name
        sort_direction_name: direction # sort direction query parameter name
        distinct: true                 # ensure distinct results, useful when ORM queries are using GROUP BY statements
    template:
        pagination: KnpPaginatorBundle:Pagination:sliding.html.twig     # sliding pagination controls template
        sortable: KnpPaginatorBundle:Pagination:sortable_link.html.twig # sort link template
```

For more configuration about these bundles, see documentation:

- [TinymceBundle](https://github.com/stfalcon/TinymceBundle)
- [StofDoctrineExtensionBundle](https://github.com/stof/StofDoctrineExtensionsBundle)
- [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)

### 4) Routing

In YAML:

```yaml
# app/config/routing.yml
arv_blog:
    resource: "@ARVBlogBundle/Resources/config/routing.yml"
    prefix:   /
```

Here you can choose prefix for your blog.

### 5) Update database schema

Launch following command:

```
php app/console doctrine:schema:update --force
```
