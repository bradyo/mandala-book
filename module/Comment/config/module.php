<?php
namespace Mandala\CommentModule;

use Zend\View\HelperPluginManager;

return array(
    'view_helpers' => array(
        'factories' => array(
            'disqus' => function (HelperPluginManager $pluginManager) {
                return new Disqus();
            }
        )
    ),
);