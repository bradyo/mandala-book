<?php
namespace Mandala\BookModule;

use Mandala\Analytics\Tracking\Event;
use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class BookController extends BaseController
{
    const ITEMS_PER_PAGE = 50;

    const SORT_NEWEST = 'newest';
    const SORT_MOST_FAVORITED = 'most-favorited';

    private $bookRepository;
    private $bookFavoriteRepository;
    private $bookManager;

    public function __construct(
        BookRepository $bookRepository,
        BookFavoriteRepository $bookFavoriteRepository,
        BookManager $bookManager
    ) {
        $this->bookRepository = $bookRepository;
        $this->bookFavoriteRepository = $bookFavoriteRepository;
        $this->bookManager = $bookManager;
    }

    public function listAction()
    {
        $criteria = new BookCriteria();
        $criteria->status = Book::STATUS_PUBLIC;

        return $this->getPaginatedViewModel($criteria);
    }

    private function getPaginatedViewModel(BookCriteria $criteria, array $order = array())
    {
        $sort = $this->params()->fromRoute('sort', self::SORT_NEWEST);
        if ($sort == self::SORT_MOST_FAVORITED) {
            $order = array('favoritedCount' => 'desc');
        } else {
            $order = array('id' => 'desc');
        }

        $page = $this->params()->fromRoute('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;
        $modelPaginator = $this->bookRepository->getPaginator($criteria, $order, $limit, $offset);
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
        $book = $this->bookRepository->find($id);

        $currentUser = $this->getCurrentUser();
        $isFavorite = $this->bookFavoriteRepository->isFavorite($currentUser, $book);
        $isOwner = ($this->getCurrentUser()->id === $book->author->id);

        return new ViewModel(array(
            'book' => $book,
            'isFavorite' => $isFavorite,
            'isOwner' => $isOwner
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
                $this->bookManager->save($book);

                $this->getTracker()->log(new Event(Event::NEW_BOOK));

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
        $book = $this->bookRepository->find($id);
        if ($book === null) {
            return $this->getNotFoundResponse('Book not found');
        }

        $user = $this->getCurrentUser();
        if ($book->author->id !== $user->id) {
            return $this->getNotAllowedResponse('Not allowed to delete book');
        }

        $this->bookManager->delete($book);

        $this->redirect()->toRoute('books');
    }
}
