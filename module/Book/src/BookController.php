<?php
namespace Mandala\BookModule;

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

    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $book = $this->getBookRepository()->find($id);
        if ($book === null) {
            return $this->getNotFoundResponse('Book not found');
        }
        $user = $this->getCurrentUser();

        return new ViewModel(array(
            'book' => $book,
            'user' => $user,
            'isOwner' => ($book->author == $user)
        ));
    }

    public function newAction()
    {
        $user = $this->getCurrentUser();
        $book = $this->getBookFactory()->createDefault($user);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->getBookManager()->save($data['title'], $user, $data['design_ids']);

            $this->redirect()->toRoute('user-books', array('userId' => $user->id));
        }
        return new ViewModel(array(
            'user' => $user,
            'book' => $book,
        ));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $book = $this->getBookRepository()->find($id);
        if ($book === null) {
            return $this->getNotFoundResponse('Book not found');
        }

        $user = $this->getCurrentUser();
        if ($book->author->id !== $user->id) {
            return $this->getNotAllowedResponse('Not allowed to delete book');
        }

        $this->getBookManager()->delete($book);

        $this->redirect()->toRoute('user-books', array('userId' => $user->id));
    }

    /**
     * @return BookRepository
     */
    private function getBookRepository()
    {
        return $this->serviceLocator->get('book_repository');
    }

    /**
     * @return BookManager
     */
    private function getBookManager()
    {
        return $this->serviceLocator->get('book_manager');
    }
}
