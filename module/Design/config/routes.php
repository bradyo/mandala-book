<?php
return array(
    'all-designs' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/designs[/page-:page]',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'index',
                'page' => 1
            ),
        ),
    ),
    'user-designs' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users/:userId/designs[/:page]',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'users',
                'page' => 1
            ),
        ),
    ),
    'favorite-designs' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users/:userId/favorites-designs[/:page]',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'favorites',
                'page' => 1
            ),
        ),
    ),
    'add-favorite-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/favorite-designs/add',
            'defaults' => array(
                'controller' => 'design_favorite',
                'action' => 'add'
            ),
        ),
    ),
    'remove-favorite-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/favorite-designs/remove',
            'defaults' => array(
                'controller' => 'design-favorite',
                'action' => 'remove'
            ),
        ),
    ),
    'new-design' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/new-design',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'new'
            ),
        ),
    ),
    'save-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/designs/:id/save',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'save'
            ),
        ),
    ),
    'show-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/designs/:id',
            'constraints' => array(
                'id' => '[0-9]+',
            ),
            'defaults' => array(
                'controller' => 'design',
                'action' => 'show',
            ),
        ),
    ),
    'get-design-thumbnail' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/data/design-files/[:id]-[:size]px.png',
            'constraints' => array(
                'id' => '[0-9]+',
                'size' => '\d+',
            ),
            'defaults' => array(
                'controller' => 'design_thumbnail',
                'action' => 'get',
            ),
        ),
    ),
    'delete-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/designs/:id/delete',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'delete'
            ),
        ),
    ),
);