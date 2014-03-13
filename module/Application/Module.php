<?php
namespace Mandala\Application;

use Mandala\Application\Listener\InjectTemplateListener;
use Zend\Log\Logger;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;

class Module extends BaseModule
{
    protected $namespace = __NAMESPACE__;
    protected $modulePath = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        parent::onBootstrap($e);

        $eventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $handler = array(new InjectTemplateListener(), 'injectTemplate');
        $eventManager->attach('Mandala', MvcEvent::EVENT_DISPATCH, $handler, -81);

        // attach logger to error event
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('dispatch.error', function($event) {
            $exception = $event->getResult()->exception;
            if ($exception) {
                $services = $event->getApplication()->getServiceManager();
                /** @var Logger $logger */
                $logger = $services->get('logger');
                $trace = $exception->getTraceAsString();
                $i = 1;
                do {
                    $messages[] = $i++ . ": " . $exception->getMessage();
                } while ($exception = $exception->getPrevious());

                $log = "Exception:n" . implode("n", $messages);
                $log .= "nTrace:n" . $trace;
                $logger->err($log);
            }
        });
    }

    public function setLayoutTitle(MvcEvent $e)
    {
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');
        $headTitleHelper = $viewHelperManager->get('headTitle');
        $headTitleHelper->setSeparator(' - ');
        $headTitleHelper->append('Mandala Maker');
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'logger' => function (ServiceManager $services) {
                    $log = new \Zend\Log\Logger();
                    $writer = new \Zend\Log\Writer\Stream(__DIR__ . '/../../data/application.log');
                    $log->addWriter($writer);
                    return $log;
                },
                'mailer' => function (ServiceManager $services) {
                    $config = $services->get('Config');
                    $transport = new Smtp();
                    $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                    return $transport;
                },
                'entity_manager' => function (ServiceManager $services) {
                    return $services->get('Doctrine\ORM\EntityManager');
                }
            ),
        );
    }
}
