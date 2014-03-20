<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;
use Zend\Session\Container;

class SessionController extends BaseController
{
    private $session;

    public function __construct(Container $session)
    {
        $this->session = $session;
    }

    public function showBooksToolbarAction()
    {
        $this->session['show_books_toolbar'] = true;

        return $this->getSuccessResponse('Success');
    }

    public function hideBooksToolbarAction()
    {
        $this->session['show_books_toolbar'] = false;

        return $this->getSuccessResponse('Success');
    }

    public function hideBooksToolbarHelpAction()
    {
        $this->session['show_books_toolbar_help'] = false;

        return $this->getSuccessResponse('Success');
    }
}
