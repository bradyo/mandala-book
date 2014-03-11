<?php
namespace Mandala\Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FacebookShareButtonHelper extends AbstractHelper
{
    protected $facebookAppId = null;

    public function __construct($facebookAppId)
    {
        $this->facebookAppId = $facebookAppId;
    }

    public function __invoke(array $params)
    {
        $params = array_merge($params, array('appId' => $this->facebookAppId));
        return $this->getView()->render('application/helper/facebook-share-button.phtml', $params);
    }
}
