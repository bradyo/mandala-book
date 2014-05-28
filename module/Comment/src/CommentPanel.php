<?php
namespace Mandala\CommentModule;

use Zend\View\Helper\AbstractHelper;

class CommentPanel extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('comment-module/comment-panel.phtml');
    }
}
