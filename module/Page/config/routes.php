<?php
return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/',
            'defaults' => array(
                'controller' => 'home',
                'action' => 'index',
            ),
        ),
    ),
);