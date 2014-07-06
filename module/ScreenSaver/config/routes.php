<?php
return array(
    'all-designs' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/designs[/:sort]',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'index',
                'sort' => 'newest'
            ),
        ),
    ),
    'user-designs' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users/:userId/designs[/:sort]',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'users',
                'sort' => 'newest'
            ),
        ),
    ),
    'favorite-designs' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users/:userId/favorite-designs[/:sort]',
            'defaults' => array(
                'controller' => 'design',
                'action' => 'favorites',
                'sort' => 'newest'
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
    'session-show-books-toolbar' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/session/show-books-toolbar',
            'defaults' => array(
                'controller' => 'session',
                'action' => 'show-books-toolbar'
            ),
        ),
    ),
    'session-hide-books-toolbar' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/session/hide-books-toolbar',
            'defaults' => array(
                'controller' => 'session',
                'action' => 'hide-books-toolbar'
            ),
        ),
    ),
    'session-hide-books-toolbar-help' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/session/hide-books-toolbar-help',
            'defaults' => array(
                'controller' => 'session',
                'action' => 'hide-books-toolbar-help'
            ),
        ),
    ),
    'design-generator' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/design-generator',
            'defaults' => array(
                'controller' => 'design-generator',
                'action' => 'generate'
            ),
        ),
    ),
);