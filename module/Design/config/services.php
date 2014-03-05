<?php
namespace Mandala\DesignModule;

use Zend\ServiceManager\ServiceManager;

return array(
    'factories' => array(
        'design_repository' => function(ServiceManager $services) {
            return $services->get('entity_manager')->getRepository('Mandala\DesignModule\Design');
        },
        'design_thumbnail_service' => function(ServiceManager $services) {
            $config = $services->get('config');
            return new DesignThumbnailService($config['design_module']['thumbnails_path']);
        },
        'design_renderer' => function(ServiceManager $services) {
            $config = $services->get('config');
            return new DesignRenderer($config['design_module']['render_script']);
        },
        'design_manager' => function(ServiceManager $services) {
            $config = $services->get('config');
            return new DesignManager(
                $services->get('entity_manager'),
                $services->get('design_renderer'),
                $config['design_module']['files_path']
            );
        },
        'design_favorite_repository' => function(ServiceManager $services) {
            return $services->get('entity_manager')->getRepository('Mandala\DesignModule\DesignFavorite');
        },
        'design_favorite_manager' => function(ServiceManager $services) {
            return new DesignFavoriteManager($services->get('entity_manager'));
        }
    ),
);