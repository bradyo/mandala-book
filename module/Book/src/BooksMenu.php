<?php
namespace Mandala\BookModule;

use Mandala\UserModule\User;
use Zend\Mvc\Application;
use Zend\View\Helper\AbstractHelper;

class BooksMenu extends AbstractHelper
{
    private $application;
    private $currentUser;

    public function __construct(Application $application, User $currentUser)
    {
        $this->application = $application;
        $this->currentUser = $currentUser;
    }

    public function __invoke()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();
        $route = $routeMatch->getMatchedRouteName();
        $sort = $routeMatch->getParam('sort');

        return $this->getView()->render('book-module/books-menu.phtml', array(
            'currentUser' => $this->currentUser,
            'sort' => $sort,
            'route' => $route
        ));
    }
}
