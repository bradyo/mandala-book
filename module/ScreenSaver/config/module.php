<?php
namespace Mandala\DesignModule;

use Zend\Di\ServiceLocator;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

return array(
    'design_module' => array(
        'scripts_path' => '/vagrant/scripts',
        'files_path' => '/vagrant/public/data/design-files',
        'thumbnails_path' => '/vagrant/public/data/design-thumbnails',
    ),
    'service_manager' => array(
        'factories' => array(
            'design_factory' => function(ServiceManager $services) {
                return new DesignFactory();
            },
            'design_repository' => function(ServiceManager $services) {
                return $services->get('entity_manager')->getRepository('Mandala\DesignModule\Design');
            },
            'design_file_service' => function(ServiceManager $services) {
                $config = $services->get('config');
                return new DesignFileService(
                    $config['design_module']['scripts_path'],
                    $config['design_module']['files_path']
                );
            },
            'design_manager' => function(ServiceManager $services) {
                return new DesignManager($services->get('entity_manager'), $services->get('design_file_service'),
                    $services->get('analytics_tracker'));
            },
            'design_favorite_repository' => function(ServiceManager $services) {
                return $services->get('entity_manager')->getRepository('Mandala\DesignModule\DesignFavorite');
            },
            'design_favorite_manager' => function(ServiceManager $services) {
                return new DesignFavoriteManager($services->get('entity_manager'));
            }
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'design' => 'Mandala\DesignModule\DesignController',
            'design_generator' => 'Mandala\DesignModule\DesignGeneratorController',
        ),
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    ),
);