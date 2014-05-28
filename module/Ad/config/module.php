<?php
namespace Mandala\AdModule;

use Zend\View\HelperPluginManager;

return array(
    'view_helpers' => array(
        'factories' => array(
            'tallAd' => function (HelperPluginManager $pluginManager) {
                return new TallAd();
            },
            'shortAd' => function (HelperPluginManager $pluginManager) {
                return new ShortAd();
            },
        )
    ),
);