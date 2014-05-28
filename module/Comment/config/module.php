<?php
namespace Mandala\CommentModule;

use Zend\View\HelperPluginManager;

return array(
    'view_helpers' => array(
        'factories' => array(
            'commentPanel' => function (HelperPluginManager $pluginManager) {
                return new CommentPanel();
            }
        )
    ),
);