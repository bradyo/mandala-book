<?php
namespace Mandala\Analytics;

use Mandala\Analytics\Reporting\AggregationController;
use Mandala\Analytics\Reporting\AnalyticsController;
use Mandala\Analytics\Tracking\Tracker;
use Zend\Di\ServiceLocator;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;
use MongoClient;

return array(
    'service_manager' => array(
        'factories' => array(
            'analytics_db' => function(ServiceManager $services) {
                $client = new MongoClient();
                return $client->selectDB('analytics');
            },
            'analytics_tracker' => function(ServiceManager $services) {
                $db = $services->get('analytics_db');
                return new Tracker($db);
            },
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'analytics' => function(ControllerManager $manager) {
                $db = $manager->getServiceLocator()->get('analytics_db');
                return new AnalyticsController($db);
            },
            'aggregation' => function(ControllerManager $manager) {
                $db = $manager->getServiceLocator()->get('analytics_db');
                return new AggregationController($db);
            },
        )
    ),
    'router' => array(
        'routes' => array(
            'reporting' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/analytics',
                    'defaults' => array(
                        'controller' => 'analytics',
                        'action' => 'index',
                    ),
                ),
            ),
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'analytics_weekly_aggregation' => array(
                    'type' => 'simple',
                    'options' => array(
                        'route' => 'analytics_weekly_aggregation [<date>]',
                        'defaults' => array(
                            'controller' => 'aggregation',
                            'action' => 'aggregateWeekly',
                            'date' => null
                        )
                    )
                )
            )
        )
    )
);