<?php
namespace Mandala\Application\Listener;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\View\Http\InjectTemplateListener as BaseInjectTemplateListener;

class InjectTemplateListener extends BaseInjectTemplateListener
{
    public function injectTemplate(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $controller = $e->getTarget();
        if (is_object($controller)) {
            $controller = get_class($controller);
        }
        if (!$controller) {
            $controller = $routeMatch->getParam('controller', '');
        }
        if (strpos($controller, 'Mandala\\') !== 0) {
            return;
        }
        parent::injectTemplate($e);
    }

    /**
     * {@inheritDoc}
     */
    protected function deriveModuleNamespace($controller)
    {
        if (!strstr($controller, '\\')) {
            return '';
        }
        $parts = explode('\\', $controller);
        return $parts[1];
    }
}