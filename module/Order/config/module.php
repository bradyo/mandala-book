<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'order' => 'Mandala\OrderModule\OrderController',
        ),
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    )
);