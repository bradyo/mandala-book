<?php
namespace Mandala\DesignModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class BookController extends BaseController
{
    const ITEMS_PER_PAGE = 50;

    public function indexAction()
    {
        $criteria = array(
            'status' => Design::STATUS_SAVED
        );
        $order = array(
            'favoritedCount' => 'desc'
        );
        $page = $this->params()->fromRoute('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;
        $modelPaginator = $this->getDesignRepository()->getPaginator($criteria, $order, $limit, $offset);

        $totalCount = $modelPaginator->count();
        $paginator = new Paginator(new ArrayAdapter(range(1, $totalCount)));
        $paginator->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'user' => $this->getCurrentUser(),
            'designs' => $modelPaginator->getIterator(),
            'paginator' => $paginator
        ));
    }

    public function usersAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }

        $page = $this->params()->fromRoute('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;
        $criteria = array(
            'author' => $user->id,
            'status' => Design::STATUS_SAVED
        );
        $modelPaginator = $this->getDesignRepository()->getPaginator($criteria, array(), $limit, $offset);

        $totalCount = $modelPaginator->count();
        $paginator = new Paginator(new ArrayAdapter(range(1, $totalCount)));
        $paginator->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel(array(
            'user' => $this->getCurrentUser(),
            'designs' => $modelPaginator->getIterator(),
            'paginator' => $paginator
        ));
    }

    public function favoritesAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }
        $designs = $this->getDesignRepository()->findFavorites($user);
        return $this->getIndexViewModel($designs);
    }

    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }
        return new ViewModel(array(
            'design' => $design,
            'user' => $this->getCurrentUser(),
            'isFavorite' => $this->isFavorite($design),
            'isOwner' => ($design->author == $this->getCurrentUser())
        ));
    }

    public function newAction()
    {
        $design = Design::createDefault($this->getCurrentUser());
        if ($this->getRequest()->isPost()) {
            $design->data = $this->getRequest()->getPost('data');
            $design->svg = '<?xml version="1.0" encoding="utf-8"?>' . $this->getRequest()->getPost('svg');

            $this->getEntityManager()->persist($design);
            $this->getEntityManager()->flush();

            $this->redirect()->toRoute('user-designs', array('userId' => $this->getCurrentUser()->id));
        }
        return new ViewModel(array('design' => $design, 'user' => $this->getCurrentUser()));
    }

    public function addFavoriteAction()
    {
        $id = (int) $this->getRequest()->getPost('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        $criteria = array(
            'user' => $this->getCurrentUser(),
            'design' => $design
        );
        $favorite = $this->getDesignFavoriteRepository()->findOneBy($criteria);
        if ($favorite == null) {
            $favorite = new DesignFavorite();
            $favorite->user = $this->getCurrentUser();
            $favorite->design = $design;
            $this->getEntityManager()->persist($favorite);

            $design->favoritedCount++;
            $this->getEntityManager()->persist($design);
            $this->getEntityManager()->flush();
        }
        return $this->getSuccessResponse('favorite design added');
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $design = $this->getDesignRepository()->find($id);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }
        if ($design->author->id !== $this->getCurrentUser()->id) {
            return $this->getNotAllowedResponse('Not allowed to delete design');
        }

        $design->status = Design::STATUS_DELETED;
        $this->getEntityManager()->persist($design);
        $this->getEntityManager()->flush();

        $this->redirect()->toRoute('user-designs', array('userId' => $this->getCurrentUser()->id));
    }

    /**
     * @return DesignRepository
     */
    private function getBookRepository()
    {
        return $this->serviceLocator->get('book_repository');
    }
}
