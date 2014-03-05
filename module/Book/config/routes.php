<?php
return array(
    'books' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/books',
            'defaults' => array(
                'controller' => 'book',
                'action' => 'index',
                'page' => 1
            ),
        ),
    ),
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
            'route' => '/users/:userId/favorites-designs',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'favorites'
            ),
        ),
    ),
    'add-favorite-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/favorite-designs/add',
            'defaults' => array(
                'controller' => 'design-favorite',
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
    'design-thumbnail' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/designs/:id/thumbnail-[:size]px.png',
            'constraints' => array(
                'id' => '[0-9]+',
                'size' => '\d+',
            ),
            'defaults' => array(
                'controller' => 'design',
                'action' => 'thumbnail',
                'size' => '164'
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