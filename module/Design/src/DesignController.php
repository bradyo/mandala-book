<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class DesignController extends BaseController
{
    const ITEMS_PER_PAGE = 50;

    public function indexAction()
    {
        $criteria = array('status' => Design::STATUS_SAVED);
        $order = array('favoritedCount' => 'desc');
        return $this->getPaginatedViewModel($criteria, $order);
    }

    public function usersAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }
        $criteria = array(
            'author' => $user,
            'status' => Design::STATUS_SAVED
        );
        return $this->getPaginatedViewModel($criteria);
    }

    public function favoritesAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }
        $criteria = array(
            'user_favorited' => $user,
            'status' => Design::STATUS_SAVED
        );
        return $this->getPaginatedViewModel($criteria);
    }

    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $currentUser = $this->getCurrentUser();
        $isFavorite = $this->getDesignFavoriteRepository()->isFavorite($currentUser, $design);
        $isOwner = ($design->author == $this->getCurrentUser());

        return $this->getViewModel(array(
            'design' => $design,
            'isFavorite' => $isFavorite,
            'isOwner' => $isOwner
        ));
    }

    public function newAction()
    {
        $user = $this->getCurrentUser();
        $design = Design::createRandom($user);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->getDesignManager()->save($user, $data);

            $this->redirect()->toRoute('user-designs', array('userId' => $user->id));
        }
        return $this->getViewModel(array('design' => $design));
    }

    public function thumbnailAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $size = (int) $this->params()->fromQuery('size', 164);
        if ($size < 10 || $size > 400) {
            return $this->getNotAllowedResponse('Size must be between 10 and 400');
        }

        $path = $this->getDesignThumbnailService()->createThumbnail($design, $size);
        $contents = file_get_contents($path);

        $response = $this->getImageResponse($contents, 'image/png');
        $response->getHeaders()
            ->addHeaderLine('Cache-Control', 'public, max-age=31556926')
            ->addHeaderLine('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31556926))
            ->addHeaderLine('Pragma', 'public');
        return $response;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $user = $this->getCurrentUser();
        if ($design->author->id !== $user->id) {
            return $this->getNotAllowedResponse('Not allowed to delete design');
        }

        $this->getDesignManager()->delete($design);

        $this->redirect()->toRoute('user-designs', array('userId' => $user->id));
    }


    private function getPaginatedViewModel(array $criteria = array(), array $order = array())
    {
        $page = $this->params()->fromRoute('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;
        $modelPaginator = $this->getDesignRepository()->getPaginator($criteria, $order, $limit, $offset);
        $designs = $modelPaginator->getIterator();

        $pager = new Paginator(new ArrayAdapter(range(1, $modelPaginator->count())));
        $pager->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $pager->setCurrentPageNumber($page);

        return $this->getViewModel(array(
            'designs' => $designs,
            'pager' => $pager
        ));
    }

    private function getViewModel(array $params)
    {
        $baseParams = array(
            'currentUser' => $this->getCurrentUser()
        );
        return new ViewModel(array_merge($baseParams, $params));
    }

    /**
     * @return DesignRepository
     */
    private function getDesignRepository()
    {
        return $this->serviceLocator->get('design_repository');
    }

    /**
     * @return DesignManager
     */
    private function getDesignManager()
    {
        return $this->serviceLocator->get('design_manager');
    }

    /**
     * @return DesignThumbnailService
     */
    private function getDesignThumbnailService()
    {
        return $this->serviceLocator->get('design_thumbnail_service');
    }

    /**
     * @return DesignFavoriteRepository
     */
    private function getDesignFavoriteRepository()
    {
        return $this->serviceLocator->get('design_favorite_repository');
    }
}
