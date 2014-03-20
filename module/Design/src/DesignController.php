<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class DesignController extends BaseController
{
    const ITEMS_PER_PAGE = 40;

    const SORT_NEWEST = 'newest';
    const SORT_MOST_FAVORITED = 'most-favorited';

    public function indexAction()
    {
        $criteria = $criteria = DesignSearchCriteria::fromArray(array(
            'status' => Design::STATUS_PUBLIC
        ));
        return $this->getIndexViewModel($criteria);
    }

    public function usersAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }
        $criteria = DesignSearchCriteria::fromArray(array(
            'author' => $user,
            'status' => Design::STATUS_PUBLIC
        ));
        return $this->getIndexViewModel($criteria);
    }

    public function favoritesAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }
        $criteria = DesignSearchCriteria::fromArray(array(
            'user_favorited' => $user,
            'status' => Design::STATUS_PUBLIC
        ));
        return $this->getIndexViewModel($criteria);
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
        $design = $this->getDesignFactory()->createRandom($user);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->getDesignManager()->save($user, $data['data']);

            $this->redirect()->toRoute('user-designs', array('userId' => $user->id));
        }
        return $this->getViewModel(array('design' => $design));
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

    private function getIndexViewModel(DesignSearchCriteria $criteria)
    {
        $routeMatch = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
        $route = $routeMatch->getMatchedRouteName();

        $sort = $this->params()->fromRoute('sort', self::SORT_NEWEST);
        if ($sort == self::SORT_MOST_FAVORITED) {
            $order = array('favoritedCount' => 'desc');
        } else {
            $order = array('id' => 'desc');
        }

        $page = $this->params()->fromQuery('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;
        $modelPaginator = $this->getDesignRepository()->getPaginator($criteria, $order, $limit, $offset);
        $designs = $modelPaginator->getIterator();

        $pager = new Paginator(new ArrayAdapter(range(1, $modelPaginator->count())));
        $pager->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $pager->setCurrentPageNumber($page);

        $session = $this->getServiceLocator()->get('user_session');

        $showBooksToolbar = false;
        if (isset($session['show_books_toolbar'])) {
            $showBooksToolbar = $session['show_books_toolbar'];
        }
        $showBooksToolbarHelp = true;
        if (isset($session['show_books_toolbar_help'])) {
            $showBooksToolbarHelp = $session['show_books_toolbar_help'];
        }

        return $this->getViewModel(array(
            'route' => $route,
            'sort' => $sort,
            'designs' => $designs,
            'pager' => $pager,
            'showBooksToolbar' => $showBooksToolbar,
            'showBooksToolbarHelp' => $showBooksToolbarHelp,
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
     * @return DesignFactory
     */
    private function getDesignFactory()
    {
        return $this->serviceLocator->get('design_factory');
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
     * @return DesignFavoriteRepository
     */
    private function getDesignFavoriteRepository()
    {
        return $this->serviceLocator->get('design_favorite_repository');
    }
}
