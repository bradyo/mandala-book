<?php
return array(
    'modules' => array(
        'Mandala\Application',
        'Mandala\UserModule',
        'Mandala\DesignModule',
        'Mandala\BookModule',
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
            'Mandala\UserModule' => './module/User',
            'Mandala\DesignModule' => './module/Design',
            'Mandala\BookModule' => './module/Book',
            'Mandala\OrderModule' => './module/Order',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'config_cache_enabled' => false, // http://stackoverflow.com/questions/17039437/zend-framework-2-cache-conform-service-factories
        'config_cache_key' => "2245023265ae4cf87d02c8b6ba991139",
        'module_map_cache_enabled' => true,
        'module_map_cache_key' => "496fe9daf9baed5ab03314f04518b928",
        'cache_dir' => "./data/cache",
    ),
);
