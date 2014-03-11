<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'home' => 'Mandala\PageModule\HomeController',
        ),
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    )
);