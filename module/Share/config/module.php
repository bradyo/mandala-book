<?php
namespace Mandala\ShareModule;

use Zend\View\HelperPluginManager;

return array(
    'view_helpers' => array(
        'factories' => array(
            'facebookShare' => function (HelperPluginManager $pluginManager) {
                $config = $pluginManager->getServiceLocator()->get('config');
                return new FacebookShare($config['facebook']['app_id']);
            },
        )
    ),
);