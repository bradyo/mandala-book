<?php
return array(
    'create-order' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/orders/create/:bookId',
            'defaults' => array(
                'controller' => 'order',
                'action' => 'create',
            ),
        ),
    ),
    'review-order' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/orders/:id/review',
            'defaults' => array(
                'controller' => 'order',
                'action' => 'review',
            ),
        ),
    ),
    'order-confirmation' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/orders/:id/confirmation',
            'defaults' => array(
                'controller' => 'order',
                'action' => 'confirmation',
            ),
        ),
    ),
);