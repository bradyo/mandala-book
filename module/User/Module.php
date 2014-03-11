<?php
namespace Mandala\UserModule;

use Mandala\Application\BaseModule;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module extends BaseModule
{
    protected $namespace = __NAMESPACE__;
    protected $modulePath = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        parent::onBootstrap($e);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 1000);
    }

    public function onDispatch(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $sessionManager = new SessionManager();
        $sessionManager->setName('mandala');
        $sessionManager->rememberMe(30 * 24 * 60 * 60); // 30 days
        $sessionManager->start();

        $session = new Container('user', $sessionManager);
        $session['started'] = 1;
        if (! isset($session['user_id'])) {
            $user = new User();

            $entityManager = $serviceManager->get('entity_manager');
            $entityManager->persist($user);
            $entityManager->flush();

            $session['user_id'] = $user->id;
        } else {
            $user = $serviceManager->get('user_repository')->findOneById($session['user_id']);
        }

        // pass current user info to view
        $viewModel = $e->getViewModel();
        $viewModel->setVariable('currentUser', $user);
    }
}
