<?php

namespace ARV\BlogBundle\Twig\Extension;


class GlobalsExtension extends \Twig_Extension
{

    protected $isSecure;
    protected $userClass;
    protected $baseTemplate;
    protected $contentEditor;
    protected $needValidation;
    protected $waitingTime;
    protected $displayEmail;
    protected $writeAsAnonymous;

    public function __construct($isSecure, $userClass, $baseTemplate, $contentEditor, $needValidation,
                                $waitingTime, $displayEmail, $writeAsAnonymous)
    {
        $this->isSecure = $isSecure;
        $this->userClass = $userClass;
        $this->baseTemplate = $baseTemplate;
        $this->contentEditor = $contentEditor;
        $this->needValidation = $needValidation;
        $this->waitingTime = $waitingTime;
        $this->displayEmail = $displayEmail;
        $this->writeAsAnonymous = $writeAsAnonymous;
    }

    public function getGlobals()
    {
        return array(
            'arvblog_is_secure' => $this->isSecure,
            'arvblog_user_class' => $this->userClass,
            'arvblog_base_template' => $this->baseTemplate,
            'arvblog_article_content_editor' => $this->contentEditor,
            'arvblog_article_need_validation' => $this->needValidation,
            'arvblog_comment_waiting_time' => $this->waitingTime,
            'arvblog_comment_display_email' => $this->displayEmail,
            'arvblog_comment_write_as_anonymous' => $this->writeAsAnonymous
        );
    }

    public function getName()
    {
        return 'ARVBlogBundle:GlobalsExtension';
    }

}
