<?php
namespace Mandala\BookModule;

use Mandala\Application\Controller\BaseController;
use Zend\Http\Response;

class BookFavoriteController extends BaseController
{
    private $bookRepository;
    private $bookFavoriteRepository;

    public function __construct(BookRepository $bookRepository, BookFavoriteRepository $bookFavoriteRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->bookFavoriteRepository = $bookFavoriteRepository;
    }

    public function addAction()
    {
        $id = (int) $this->params()->fromPost('id');
        $book = $this->bookRepository->find($id);
        if ($book === null) {
            return $this->getNotFoundResponse('Book not found');
        }

        $user = $this->getCurrentUser();
        $this->bookFavoriteRepository->add($user, $book);

        return $this->getSuccessResponse('Book favorite added');
    }

    public function removeAction()
    {
        $id = (int) $this->params()->fromPost('id');
        $book = $this->bookRepository->find($id);
        if ($book === null) {
            return $this->getNotFoundResponse('Book not found');
        }

        $user = $this->getCurrentUser();
        $this->bookFavoriteRepository->remove($user, $book);

        return $this->getSuccessResponse('Book favorite removed');
    }
}
