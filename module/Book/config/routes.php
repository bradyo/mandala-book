<?php
return array(
    'books' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/books[/:sort]',
            'defaults' => array(
                'controller' => 'book',
                'action' => 'list',
                'sort' => 'newest',
                'page' => 1
            ),
        ),
    ),
    'show-book' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/books/:id',
            'constraints' => array(
                'id' => '[0-9]+',
            ),
            'defaults' => array(
                'controller' => 'book',
                'action' => 'show',
            ),
        ),
    ),
    'user-books' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users/:userId/books[/:sort]',
            'defaults' => array(
                'controller' => 'user_book',
                'action' => 'list',
                'sort' => 'newest'
            ),
        ),
    ),
    'user-favorite-books' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/users/:userId/favorite-books[/:sort]',
            'defaults' => array(
                'controller' => 'user_favorite_book',
                'action' => 'list',
                'sort' => 'newest'
            ),
        ),
    ),
    'add-favorite-book' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/favorite-books/add',
            'defaults' => array(
                'controller' => 'book_favorite',
                'action' => 'add'
            ),
        ),
    ),
    'remove-favorite-book' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/favorite-books/remove',
            'defaults' => array(
                'controller' => 'book_favorite',
                'action' => 'remove'
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
    ),
    'delete-book' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/books/:id/delete',
            'defaults' => array(
                'controller' => 'book',
                'action' => 'delete',
            ),
        ),
    )
);