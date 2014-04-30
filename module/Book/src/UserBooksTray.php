<?php
namespace Mandala\BookModule;

use Mandala\UserModule\User;
use Zend\View\Helper\AbstractHelper;

class UserBooksTray extends AbstractHelper
{
    protected $user;
    protected $bookRepository;

    public function __construct(User $user, BookRepository $bookRepository)
    {
        $this->user = $user;
        $this->bookRepository = $bookRepository;
    }

    public function __invoke($showHelp = true)
    {
        $criteria = new BookCriteria();
        $criteria->status = Book::STATUS_PUBLIC;
        $criteria->author = $this->user;

        $paginator = $this->bookRepository->getPaginator($criteria);
        $books = $paginator->getIterator();

        return $this->getView()->render('book-module/user-books-tray.phtml', array(
            'books' => $books,
            'showHelp' => $showHelp
        ));
    }
}
