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
        $criteria = new BookCriteria();
        $criteria->status = Book::STATUS_PUBLIC;
        $criteria->author = $this->getCurrentUser();

        return $this->getPaginatedViewModel($criteria);
    }

    private function getPaginatedViewModel(BookCriteria $criteria, array $order = array())
    {
        $page = $this->params()->fromRoute('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;
        $modelPaginator = $this->getBookRepository()->getPaginator($criteria, $order, $limit, $offset);
        $books = $modelPaginator->getIterator();

        $pager = new Paginator(new ArrayAdapter(range(1, $modelPaginator->count())));
        $pager->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $pager->setCurrentPageNumber($page);

        return new ViewModel(array(
            'books' => $books,
            'pager' => $pager
        ));
    }

    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $book = $this->getBookRepository()->find($id);

        return new ViewModel(array(
            'book' => $book
        ));
    }

    public function newAction()
    {
        $form = new NewBookForm();
        $form->setInputFilter(new NewBookPostFilter());
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $book = new Book();
                $book->title = $data['title'];
                $book->author = $this->getCurrentUser();
                $this->getBookManager()->save($book);

                $this->redirect()->toRoute('books');
            }
        }
        return new ViewModel(array(
            'form' => $form
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
