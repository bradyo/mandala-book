<?php
namespace Mandala\BookModule;

use Zend\ServiceManager\ServiceManager;

return array(
    'book_module' => array(
        'book_files_path' => '/vagrant/public/data/book-files',
    ),
    'service_manager' => array(
        'factories' => array(
            'book_repository' => function(ServiceManager $services) {
                return $services->get('entity_manager')->getRepository('Mandala\BookModule\Book');
            },
            'book_file_service' => function(ServiceManager $services) {
                    $config = $services->get('config');
                    return new BookFileService(
                        $config['book_module']['book_output_path'],
                        $services->get('design_file_service')
                    );
                },
            'book_manager' => function(ServiceManager $services) {
                return new BookManager(
                    $services->get('entity_manager'),
                    $services->get('book_file_service')
                );
            },
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'book' => 'Mandala\BookModule\BookController',
        ),
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    )
);