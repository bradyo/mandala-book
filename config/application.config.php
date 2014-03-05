<?php
return array(
    'modules' => array(
        'Mandala\Application',
        'Mandala\PageModule',
        'Mandala\UserModule',
        'Mandala\DesignModule',
        'Mandala\OrderModule',
        'ZendDeveloperTools',
        'DoctrineModule',
        'DoctrineORMModule',
        'TwbBundle',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
            'Mandala\Application' => './module/Application',
            'Mandala\PageModule' => './module/Page',
            'Mandala\UserModule' => './module/User',
            'Mandala\DesignModule' => './module/Design',
            'Mandala\OrderModule' => './module/Order',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
