<?php
namespace Mandala\OrderModule;

use Guzzle\Http\Client;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

return array(
    'order_module' => array(
        'pdf_output_path' => '/vagrant/public/data/order-files',
    ),
    'service_manager' => array(
        'factories' => array(
            'order_factory' => function(ServiceManager $services) {
                return new OrderFactory();
            },
            'book_file_maker' => function(ServiceManager $services) {
                $config = $services->get('Config');
                $outputPath = $config['order_module']['pdf_output_path'];
                $designFileService = $services->get('design_file_service');
                return new BookFileMaker($outputPath, $designFileService);
            },
            'stripe_client' => function(ServiceManager $services) {
                $config = $config = $services->get('Config');;
                $client = new Client('https://api.stripe.com', array(
                    'request.options' => array(
                        'auth' => array($config['stripe']['secret_key'] . ':', '', 'Basic')
                    ),
                ));
                return $client;
            },
            'order_processor' => function(ServiceManager $services) {
                $bookFileMaker = $services->get('book_file_maker');
                $em = $services->get('entity_manager');
                $stripeClient = $services->get('stripe_client');
                $mailer = $services->get('mailer');
                return new OrderProcessor($bookFileMaker, $em, $stripeClient, $mailer);
            },
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'order' => function(ControllerManager $controllerManager) {
                $services = $controllerManager->getServiceLocator();
                $orderFactory = $services->get('order_factory');
                $orderProcessor = $services->get('order_processor');
                return new OrderController($orderFactory, $orderProcessor);
            },
        )
    ),
    'router' => array(
        'routes' => require(__DIR__ . '/routes.php')
    ),
);