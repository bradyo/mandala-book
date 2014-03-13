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
    'show-book' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/books/:id',
            'defaults' => array(
                'controller' => 'book',
                'action' => 'show',
            ),
        ),
    ),
    'new-book' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/new-book',
            'defaults' => array(
                'controller' => 'book',
                'action' => 'new',
            ),
        ),
    ),
    'add-book-design' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/book-designs',
            'defaults' => array(
                'controller' => 'book_design',
                'action' => 'add',
            ),
        ),
    )
);