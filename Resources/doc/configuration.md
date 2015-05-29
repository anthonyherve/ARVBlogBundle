# Configuration of ARVBlogBundle

This documentation describes all parameters to enable/disable some features.

## Default configuration

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

## Global parameters

### base_template

By default, the base template is null and all template inherits from *blog_base.html.twig* below.

```twig
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}{% endblock %}
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
        <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
    </head>
    <body>
    {% block body %}
        {% block content %}

        {% endblock %}
    {% endblock %}
    {% block javascripts %}{% endblock %}
    </body>
</html>
```

In you want to change this parameter, you have to put a value like this : **AcmeBundle:Template:base.html.twig**.
The template should at least contain a block named "content".

If you put a wrong template, you will have following error 500 :
**_Unable to find template "WrongTemplate:Path:base.html.twig" in_**.

### user_class

By default, the user class is null and articles and comments are not linked to a user. If you want to do these links, 
you have to change *user_class* parameter (for example, Acme\Bundle\Entity\User).

This user entity should have at least a property named *username*.

If you put a wrong class, you will have a MappingException : 
 **_The target-entity Acme\Bundle\Entity\WrongClass cannot be found in 'ARV\BlogBundle\Entity\Article#user'._**

### is_secure

By default, this parameter is set to *false*. It means that all functions and routes are available for any user.
If you set it to *true*, the bundle is secure and you have to define at least two roles :

* ROLE_USER : add a comment, see articles
* ROLE_ADMIN : allowed to access all functions


## Article

### content_editor

By default, this parameter is null. It means that in article's form, content will be displayed as a normal textarea. 
This parameter accepts values below :

* tinymce : use TinymceBundle to display a WYSIWYG textarea


**_Other values will be possible for next version of bundle._**

### need_validation

By default, this parameter is set to *false*. If it's set to *true*, it means when you write an article, you'll see 
a date field. This field indicates the date on which the article will be public.

## Comment

### waiting_time

By default, this parameter is set to *0*. It's the required time (in minutes) between two comments from the same IP address. 
It can be used to prevent robots posting multiple comments.

### display_email

By default, this parameter is set to *true*. It displays or not a field "email" in comment's form.

### write_as_anonymous

By default, this parameter is set to *true*. When it's true, an anonymous user can write a comment. If it's false, 
a user has to be authenticated to post a comment.
