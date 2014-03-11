<?php
return array(
    'sign-up' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/sign-up',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'signUp',
            ),
        ),
    ),
    'sign-in' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/sign-in',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'signIn',
            ),
        ),
    ),
    'sign-out' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/sign-out',
            'defaults' => array(
                'controller' => 'user',
                'action' => 'signOut',
            ),
        ),
    ),
);