# BlogBundle

This bundle is in development.

## Installation

Add this line to your composer.json:

```php
"arv/blogbundle":"0.0.1"
```

Add these lines to your AppKernel.php:

```php
new ARV\BlogBundle\ARVBlogBundle(),
new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
```

Add these lines to your config.yml:

```yaml
# StofDoctrineExtensions configuration
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
            sluggable:     true
```