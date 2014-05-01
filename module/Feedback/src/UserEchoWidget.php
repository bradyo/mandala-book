<?php
namespace Mandala\FeedbackModule;

use Zend\View\Helper\AbstractHelper;

class UserEchoWidget extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('feedback-module/user-echo-widget.phtml');
    }
} 