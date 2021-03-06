<?php
namespace Mandala\BookModule;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Mandala\UserModule\User;

class BookRepository extends EntityRepository
{
    public function getPaginator(BookCriteria $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        $builder = $this->createQueryBuilder('b');
        $builder->leftJoin('b.bookDesigns', 'bd');
        $builder->leftJoin('bd.design', 'd');
        if ($criteria->status !== null) {
            $builder->andWhere('b.status = :status');
            $builder->setParameter(':status', $criteria->status);
        }
        if ($criteria->author !== null) {
            $builder->andWhere('b.author = :author');
            $builder->setParameter(':author', $criteria->author);
        }

        foreach ($orderBy as $sort => $order) {
            $builder->addOrderBy('b.' . $sort, $order);
        }
        if ($limit) {
            $builder->setMaxResults($limit);
        }
        if ($offset) {
            $builder->setFirstResult($offset);
        }
        $query = $builder->getQuery();
        return new Paginator($query);
    }

    public function getFavoritedBookIds(User $user) {
        $dql = '
            SELECT DISTINCT b.id
            FROM Mandala\BookModule\Book b
            INNER JOIN Mandala\BookModule\BookFavorite f WITH f.book = b
            WHERE f.user = :user
        ';
        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);
        $ids = array_map('current', $query->getScalarResult());

        return $ids;
    }
}