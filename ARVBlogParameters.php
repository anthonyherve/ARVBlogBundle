<?php

namespace ARV\BlogBundle;


/**
 * Constants for parameters bundle.
 * Class ARVBlogParameters
 * @package ARV\BlogBundle
 */
final class ARVBlogParameters
{

    // Names of parameters
    const IS_SECURE = 'arv_blog.is_secure';
    const USER_CLASS = 'arv_blog.user_class';
    const BASE_TEMPLATE = 'arv_blog.base_template';
    const CONTENT_EDITOR = 'arv_blog.article.content_editor';
    const NEED_VALIDATION = 'arv_blog.article.need_validation';
    const WAITING_TIME = 'arv_blog.comment.waiting_time';
    const DISPLAY_EMAIL = 'arv_blog.comment.display_email';
    const WRITE_AS_ANONYMOUS = 'arv_blog.comment.write_as_anonymous';

    // Values of parameters
    const EDITOR_TINYMCE = 'tinymce';

}
