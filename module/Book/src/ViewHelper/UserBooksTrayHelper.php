<?php
namespace Mandala\BookModule\ViewHelper;

use Mandala\BookModule\BookCriteria;
use Mandala\BookModule\BookRepository;
use Mandala\UserModule\User;
use Zend\View\Helper\AbstractHelper;

class UserBooksTrayHelper extends AbstractHelper
{
    protected $user;
    protected $bookRepository;

    public function __construct(User $user, BookRepository $bookRepository)
    {
        $this->user = $user;
        $this->bookRepository = $bookRepository;
    }

    public function __invoke()
    {
        $criteria = new BookCriteria();
        $criteria->author = $this->user;
        $paginator = $this->bookRepository->getPaginator($criteria);
        $books = $paginator->getIterator();

        return $this->getView()->render('book-module/helper/user-books-tray.phtml', array(
            'books' => $books
        ));
    }
}