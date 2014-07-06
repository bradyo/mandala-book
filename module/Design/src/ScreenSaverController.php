<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class ScreenSaverController extends BaseController
{
    public function indexAction()
    {
        $design =  $this->getServiceLocator()->get('design_factory')->createScreenSaverDesign();

        return new ViewModel(array(
            'design' => $design
        ));
    }
}