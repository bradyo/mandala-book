<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

class DesignGeneratorController extends BaseController
{
    public function generateAction()
    {
        $design =  $this->getServiceLocator()->get('design_factory')->createFancyDesign();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $design = $this->getServiceLocator()->get('design_manager')->save($this->getCurrentUser(), $data['data']);

            $this->redirect()->toRoute('design-generator');
        }

        return new ViewModel(array(
            'design' => $design
        ));
    }
}