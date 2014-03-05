<?php
namespace Mandala\Application;

use Mandala\Application\Mvc\View\Http\InjectTemplateListener;
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
                    return null;
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
