<?php
namespace Mandala\BookModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;

class UserFavoriteBookController extends BaseController
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function listAction()
    {
        $userId = (int) $this->params()->fromRoute('userId');
        $user = $this->getUserRepository()->find($userId);
        if ($user === null) {
            return $this->getNotFoundResponse('User not found');
        }

        $criteria = new BookCriteria();
        $criteria->status = Book::STATUS_PUBLIC;
        $criteria->favoritedBy = $user;

        $order = array();
        $page = $this->params()->fromRoute('page', 1);

        return new BooksViewModel($this->bookRepository, $criteria, $order, $page);
    }
}
