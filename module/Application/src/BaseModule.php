<?php
namespace Mandala\Application;

use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

abstract class BaseModule implements ServiceProviderInterface, ConfigProviderInterface, AutoloaderProviderInterface
{
    protected $namespace;
    protected $modulePath;

    public function onBootstrap(MvcEvent $e)
    {
    }

    public function getConfig()
    {
        $doctrineDriverKey = $this->namespace . '_driver';
        $config = array(
            'doctrine' => array(
                'driver' => array(
                    $doctrineDriverKey => array(
                        'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                        'cache' => 'array',
                        'paths' => array($this->modulePath . '/src')
                    ),
                    'orm_default' => array(
                        'drivers' => array(
                            $this->namespace => $doctrineDriverKey
                        )
                    )
                )
            ),
            'view_manager' => array(
                'template_path_stack' => array(
                    $this->namespace => $this->modulePath . '/view',
                ),
            ),
        );

        $routeConfigPath = $this->modulePath . '/config/routes.php';
        if (file_exists($routeConfigPath)) {
            $config = array_merge($config, array(
                'router' => array(
                    'routes' => require($routeConfigPath)
                ),
            ));
        }
        return array_merge($config, require($this->modulePath . '/config/module.php'));
    }

    public function getServiceConfig()
    {
        $config = array();
        $configPath = $this->modulePath . '/config/services.php';
        if (file_exists($configPath)) {
            $config = require($this->modulePath . '/config/services.php');
        }
        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $this->namespace => $this->modulePath . '/src',
                ),
            ),
        );
    }
}