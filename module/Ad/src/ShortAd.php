<?php
namespace Mandala\AdModule;

use Zend\View\Helper\AbstractHelper;

class ShortAd extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('ad-module/short-ad.phtml');
    }
}
