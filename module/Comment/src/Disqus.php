<?php
namespace Mandala\CommentModule;

use Zend\View\Helper\AbstractHelper;

class Disqus extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('comment-module/disqus.phtml');
    }
}
