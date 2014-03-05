<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;

class DesignFavoriteController extends BaseController
{
    public function addAction()
    {
        $id = (int) $this->params()->fromPost('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }
        $user = $this->getCurrentUser();
        $this->getDesignFavoriteManager()->add($user, $design);

        return $this->getSuccessResponse('favorite design added');
    }

    public function removeAction()
    {
        $id = (int) $this->params()->fromPost('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }
        $user = $this->getCurrentUser();
        $this->getDesignFavoriteManager()->remove($user, $design);

        return $this->getSuccessResponse('Design favorite removed');
    }

    /**
     * @return DesignRepository
     */
    private function getDesignRepository()
    {
        return $this->serviceLocator->get('design_repository');
    }

    /**
     * @return DesignFavoriteManager
     */
    private function getDesignFavoriteManager()
    {
        return $this->serviceLocator->get('design_favorite_manager');
    }
}
