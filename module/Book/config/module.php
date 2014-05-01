<?php
namespace Mandala\BookModule;

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
            'book_favorite_repository' => function(ServiceManager $services) {
                    return $services->get('entity_manager')->getRepository('Mandala\BookModule\BookFavorite');
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
                $currentUser = $services->get('current_user');
                $booksRepository = $services->get('book_repository');
                return new UserBooksTray($currentUser, $booksRepository);
            },
            'booksMenu' => function (HelperPluginManager $helperPluginManager) {
                $services = $helperPluginManager->getServiceLocator();
                $application = $services->get('application');
                $currentUser = $services->get('current_user');
                return new BooksMenu($application, $currentUser);
            },
            'booksSortMenu' => function (HelperPluginManager $helperPluginManager) {
                $services = $helperPluginManager->getServiceLocator();
                $application = $services->get('application');
                return new BooksSortMenu($application);
            }
        )
    ),
    'controllers' => array(
        'factories' => array(
            'book' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                $bookRepository = $services->get('book_repository');
                $bookFavoriteRepository = $services->get('book_favorite_repository');
                $bookManager = $services->get('book_manager');
                return new BookController($bookRepository, $bookFavoriteRepository, $bookManager);
            },
            'book_design' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                $bookRepository = $services->get('book_repository');
                $designRepository = $services->get('design_repository');
                $bookManager = $services->get('book_manager');
                return new BookDesignController($bookRepository, $designRepository, $bookManager);
            },
            'book_favorite' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                $bookRepository = $services->get('book_repository');
                $bookFavoriteRepository = $services->get('book_favorite_repository');
                return new BookFavoriteController($bookRepository, $bookFavoriteRepository);
            },
            'user_book' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                $bookRepository = $services->get('book_repository');
                $currentUser = $services->get('current_user');
                return new UserBookController($bookRepository, $currentUser);
            },
            'user_favorite_book' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                $bookRepository = $services->get('book_repository');
                $currentUser = $services->get('current_user');
                return new UserFavoriteBookController($bookRepository, $currentUser);
            },
        )
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    )
);