<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'home' => 'Mandala\Application\Controller\HomeController',
            'update' => 'Mandala\Application\Controller\UpdateController',
        ),
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    ),
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
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'mailer' => function(\Zend\ServiceManager\ServiceManager $services) {
                $config = $services->get('Config');
                $mailer = new \Zend\Mail\Transport\Smtp();
                $mailer->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['mail']['transport']['options']));
                return $mailer;
            },
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
    'console' => array(
        'router' => array(
            'routes' => array(
                'update' => array(
                    'type' => 'simple',
                    'options' => array(
                        'route' => 'update <date>',
                        'defaults' => array(
                            'controller' => 'update',
                            'action' => 'update',
                        )
                    )
                )
            )
        )
    )
);
