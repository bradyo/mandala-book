<?php
namespace Mandala\Application;

use Mandala\Application\View\Helper\FacebookShareButtonHelper;
use Zend\View\HelperPluginManager;

return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/application/layout.phtml',
            'error/404' => __DIR__ . '/../view/application/error/404.phtml',
            'error/index' => __DIR__ . '/../view/application/error/index.phtml',
        ),
        'template_path_stack' => array(
            'application' => __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'facebookShareButton' => function (HelperPluginManager $helperPluginManager) {
                $config = $helperPluginManager->getServiceLocator()->get('config');
                return new FacebookShareButtonHelper($config['facebook']['app_id']);
            }
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);
