<?php
namespace Mandala\BookModule;

use Zend\Mvc\Application;
use Zend\View\Helper\AbstractHelper;

class BooksSortMenu extends AbstractHelper
{
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function __invoke()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();
        $route = $routeMatch->getMatchedRouteName();
        $sort = $routeMatch->getParam('sort', 'newest');

        return $this->getView()->render('book-module/books-sort-menu.phtml', array(
            'sort' => $sort,
            'route' => $route
        ));
    }
}
