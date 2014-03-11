<?php
namespace Mandala\UserModule;

use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;

return array(
    'service_manager' => array(
        'factories' => array(
            'user_repository' => function (ServiceManager $sm) {
                    return $sm->get('entity_manager')->getRepository('Mandala\UserModule\User');
                },
            'current_user' => function (ServiceManager $sm) {
                    $session = new Container('user');
                    if (! isset($session['user_id'])) {
                        $user = new User();
                        $sm->get('entity_manager')->persist($user);
                        $sm->get('entity_manager')->flush();
                        $session ['user_id'] = $user->id;
                    }
                    return $sm->get('user_repository')->find($session['user_id']);
                },
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'user' => 'Mandala\UserModule\UserController',
        ),
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    )
);