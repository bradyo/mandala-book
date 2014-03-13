<?php
namespace Mandala\BookModule;

use Mandala\Application\Controller\BaseController;
use Mandala\DesignModule\DesignRepository;

class BookDesignController extends BaseController
{
    private $bookRepository;
    private $designRepository;
    private $bookManager;

    public function __construct(
        BookRepository $bookRepository,
        DesignRepository $designRepository,
        BookManager $bookManager
    ) {
        $this->bookRepository = $bookRepository;
        $this->designRepository = $designRepository;
        $this->bookManager = $bookManager;
    }

    public function addAction()
    {
        $bookId = (int) $this->params()->fromPost('bookId');
        $book = $this->bookRepository->find($bookId);
        if ($book === null) {
            return $this->getNotFoundResponse('Book not found');
        }

        $currentUser = $this->getCurrentUser();
        if ($book->author !== $currentUser) {
            return $this->getNotAllowedResponse('Not allowed to add designs to this book');
        }

        $designId = (int) $this->params()->fromPost('designId');
        $design = $this->designRepository->find($designId);
        if ($design === null) {
            return $this->getNotFoundResponse('Design not found');
        }

        if ($book->containsDesign($design)) {
            return $this->getConflictResponse('Design already added to book');
        }

        $book->addDesign($design);
        $this->bookManager->save($book);

        return $this->getSuccessResponse('Design added to book');
    }
}
