<?php
namespace Mandala\ShareModule;

use Zend\View\Helper\AbstractHelper;

class FacebookShare extends AbstractHelper
{
    protected $facebookAppId = null;

    public function __construct($facebookAppId)
    {
        $this->facebookAppId = $facebookAppId;
    }

    public function __invoke(array $params)
    {
        $params = array_merge($params, array('appId' => $this->facebookAppId));
        return $this->getView()->render('share-module/facebook-share.phtml', $params);
    }
}
