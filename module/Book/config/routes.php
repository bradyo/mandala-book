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
);