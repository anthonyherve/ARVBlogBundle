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
new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
```

Add these lines to your config.yml:

```yaml
# StofDoctrineExtensions configuration
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
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
```