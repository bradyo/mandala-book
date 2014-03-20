<?php
namespace Mandala\BookModule;

use Mandala\BookModule\ViewHelper\UserBooksTrayHelper;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

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
                    $config['book_module']['book_files_path'],
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
    'view_helpers' => array(
        'factories' => array(
            'userBooksTray' => function (HelperPluginManager $helperPluginManager) {
                $services = $helperPluginManager->getServiceLocator();
                return new UserBooksTrayHelper(
                    $services->get('current_user'),
                    $services->get('book_repository')
                );
            }
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'book' => 'Mandala\BookModule\BookController',
        ),
        'factories' => array(
            'book' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                return new BookController(
                    $services->get('book_repository'),
                    $services->get('book_manager')
                );
            },
            'book_design' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                return new BookDesignController(
                    $services->get('book_repository'),
                    $services->get('design_repository'),
                    $services->get('book_manager')
                );
            },
        )
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    )
);