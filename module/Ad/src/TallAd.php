<?php
namespace Mandala\AdModule;

use Zend\View\Helper\AbstractHelper;

class TallAd extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render('ad-module/tall-ad.phtml');
    }
}
