<?php
namespace Mandala\BookModule;

use Mandala\UserModule\User;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class BooksViewModel extends ViewModel
{
    const ITEMS_PER_PAGE = 40;

    public function __construct(BookRepository $bookRepository, BookCriteria $criteria, array $order = array(), $page = 0)
    {
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        $limit = self::ITEMS_PER_PAGE;

        $builder = $bookRepository->createQueryBuilder('b');
        if ($criteria->status !== null) {
            $builder->andWhere('b.status = :status');
            $builder->setParameter(':status', $criteria->status);
        }
        if ($criteria->author !== null) {
            $builder->andWhere('b.author = :author');
            $builder->setParameter(':author', $criteria->author);
        }
        if ($criteria->favoritedBy !== null) {
            $ids = $bookRepository->getFavoritedBookIds($criteria->favoritedBy);
            $builder->andWhere('b.id IN (:ids)');
            $builder->setParameter('ids', $ids);
        }

        foreach ($order as $field => $direction) {
            $builder->addOrderBy('b.' . $field, $direction);
        }
        if ($limit) {
            $builder->setMaxResults($limit);
        }
        if ($offset) {
            $builder->setFirstResult($offset);
        }
        $query = $builder->getQuery();
        $modelPaginator = new DoctrinePaginator($query);
        $books = $modelPaginator->getIterator();

        $pager = new Paginator(new ArrayAdapter(range(1, $modelPaginator->count())));
        $pager->setItemCountPerPage(self::ITEMS_PER_PAGE);
        $pager->setCurrentPageNumber($page);

        parent::__construct(array(
            'books' => $books,
            'pager' => $pager
        ));
    }
}