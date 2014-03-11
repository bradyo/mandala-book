<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;

class DesignFavoriteController extends BaseController
{
    private $designRepository;
    private $designFavoriteManager;

    public function __construct(DesignRepository $designRepository, DesignFavoriteManager $designFavoriteManager)
    {
        $this->designRepository = $designRepository;
        $this->designFavoriteManager = $designFavoriteManager;
    }

    public function addAction()
    {
        $id = (int) $this->params()->fromPost('id');
        $design = $this->designRepository->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $user = $this->getCurrentUser();
        $this->designFavoriteManager->add($user, $design);

        return $this->getSuccessResponse('Design favorite added');
    }

    public function removeAction()
    {
        $id = (int) $this->params()->fromPost('id');
        $design = $this->designRepository->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $user = $this->getCurrentUser();
        $this->designFavoriteManager->remove($user, $design);

        return $this->getSuccessResponse('Design favorite removed');
    }
}
