<?php
namespace Mandala\ScreenSaver;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

class HomeController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
