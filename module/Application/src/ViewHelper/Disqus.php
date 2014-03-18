<?php
namespace Mandala\Application\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class Disqus extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('application/helper/disqus.phtml');
    }
}
