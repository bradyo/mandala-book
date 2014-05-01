<?php
namespace Mandala\FeedbackModule;

use Zend\View\HelperPluginManager;

return array(
    'view_helpers' => array(
        'factories' => array(
            'userEchoWidget' => function (HelperPluginManager $pluginManager) {
                return new UserEchoWidget();
            },
        )
    ),
);